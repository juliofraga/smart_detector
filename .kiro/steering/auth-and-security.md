# Auth & Security

## Authentication Flow

### Login
1. Vue `Login.vue` POSTs credentials to `POST /api/login` (public, no JWT required)
2. `AuthController::login()` validates user, checks block status, attempts JWT auth
3. On success: returns `{ token: '...' }` with HTTP 201
4. Browser stores token in a cookie: `token=<jwt>:SameSite=Lax`
5. On subsequent requests, `bootstrap.js` Axios interceptor reads the cookie and injects `Authorization: Bearer <token>` into every request header

### JWT Middleware
- Route middleware: `jwt.auth` → `Tymon\JWTAuth\Http\Middleware\Authenticate`
- All `/api/v1/` routes require this middleware
- WebSocket broadcast auth (`/api/broadcasting/auth`) also uses JWT headers

### Token Refresh — Known Bug
`bootstrap.js` intercepts 401 responses with `message == 'Token has expired'` and calls `POST /api/refresh`. This route **does not exist** in `routes/api.php`. The refresh will always fail silently. Users with expired tokens must log in again.

### Logout
`POST /api/v1/logout` calls `auth('api')->logout()` which invalidates the JWT. The cookie is not cleared server-side — the frontend must clear it manually.

---

## Authorization

### Role Model
Two hardcoded profiles seeded at install:
- `Administrador` — full access
- `Usuário` — read-only access to events and dashboards

### AdminMiddleware
```php
Auth::user()->profile->description === 'Administrador'
```
This is a **hardcoded Portuguese string comparison**. If the profile description is ever changed in the database, admin access breaks silently. Do not rename the 'Administrador' profile.

### Admin-protected API routes
- `POST/GET/DELETE /api/v1/user`
- `POST/GET/PATCH/DELETE /api/v1/classification`
- `POST/GET/PATCH/DELETE /api/v1/type`
- `POST/GET/PATCH/DELETE /api/v1/event-attribute`
- `GET/PATCH /api/v1/system-settings`
- `POST/GET/PATCH/DELETE /api/v1/ids`

### Public routes (no JWT)
- `POST /api/login`
- `POST /api/user/update-password/`
- `GET /api/translation/{domain}`

---

## Login Protection

Controlled by `system_settings.block_user` (Yes/No).

When enabled:
- Failed login increments `login_errors.error_count` for that user
- After 5 consecutive failures: account is blocked (`blocked=1`, `blocked_at=now()`)
- Blocked accounts are rejected for **10 minutes** from `blocked_at`
- Successful login resets `error_count`, `blocked`, and `blocked_at`

When `block_user=No`: failed logins are not tracked at all.

**Important:** If the `system_settings` table is missing or `block_user` is not seeded, `AuthController::login()` returns HTTP 401 with a system configuration error and login is impossible.

---

## Forced Password Change

- `users.updated_pass = 0` means the user must change their password before accessing the system
- The default seeded admin has `updated_pass = 0`
- On login with `updated_pass = 0`: HTTP 428 is returned with `{ error: '...', status: 428 }`
- `Login.vue` detects status 428 and redirects to `/login/update-password?email=...`
- `UserController::updatePassword()` verifies the current password, sets the new one, then sets `updated_pass = 1`
- When an admin resets another user's password via `PATCH /api/v1/user/{id}`, `updated_pass` is set back to `0` — forcing a change on next login

---

## Password Complexity

Controlled by `system_settings.pass_complexity` (Yes/No).

When enabled, passwords must have:
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one digit
- At least one special character (`\W` or `_`)

Enforced in `PasswordValidationTrait::passwordValidate()`, called from `BaseController::store()`, `UserController::update()`, and `UserController::updatePassword()`.

---

## LLM API Key Storage

LLM `api_key` values are encrypted with Laravel's `encrypt()` before storage and decrypted with `decrypt()` when needed. They are never returned to the frontend in encrypted or plain form through normal list endpoints — only `getDefault()` decrypts and returns the key (used internally for LLM calls).

---

## CORS Configuration

`config/cors.php` is configured with:
- `allowed_origins: ['*']` — all origins allowed
- `supports_credentials: true`

This is a broad open configuration. Appropriate for a local/internal tool but should be restricted to known origins in a production deployment.

---

## Known Security Considerations

- JWT stored in a plain cookie (`SameSite=Lax`, not `HttpOnly`) — accessible to JavaScript, making it vulnerable to XSS
- Token refresh endpoint referenced in frontend does not exist
- CORS allows all origins
- Admin role check depends on a hardcoded Portuguese string in the database
- `API_URL` is hardcoded to `http://localhost:8000` in `utils/functions.js` — plain HTTP
