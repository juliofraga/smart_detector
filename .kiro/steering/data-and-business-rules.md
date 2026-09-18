# Data & Business Rules

## System Settings Reference

All settings live in the `system_settings` table and are loaded into Laravel config at boot via `SystemSettingsServiceProvider`. Access them with `config('system_settings.<attribute>')`.

| Attribute | Type | Default | Effect |
|---|---|---|---|
| `block_user` | YesNo | Yes | Enables login blocking after 5 failed attempts |
| `pass_complexity` | YesNo | Yes | Enforces strong password rules |
| `all_events` | YesNo | No | When No: only Intrusion events accepted. When Yes: both Intrusion and Normal accepted |
| `use_smart_detector_ia` | YesNo | No | When Yes: routes incoming events through the configured LLM for classification (BETA — currently non-functional, see below) |
| `request_per_minute` | text | 1000 | Max event ingests per minute (hard cap: 5000) |
| `timezone_selected` | select | America/Sao_Paulo | Applied to PHP runtime timezone and Laravel config |
| `select_language` | picklist | pt_BR | System default locale; overridden per-user by `app_locale` cookie |
| `llm_standard` | select | 0 | ID of the default LLM model used for AI analysis |
| `llm_prompt` | textarea | (default IDS analysis prompt) | Prompt sent to LLM with each event payload |

Settings with `active = 0` are hidden from the System Settings UI but still exist in the config.

---

## Event Ingest Business Rules

The most logic-heavy part of the system. `EventController::store()` applies these rules in order:

### 1. Optional LLM classification
If `use_smart_detector_ia = Yes`: sends the full event payload to the configured LLM. The LLM response is expected to return `{ intrusion_normal, analysys }`. **This feature is BETA and currently non-functional** — `LlmController::analyzeIa()` parses the response but returns a hardcoded result (`Intrusion`/`teste`). Do not rely on it.

### 2. Intrusion/Normal field resolution
- If `all_events = No`: the `intrusion_normal` field is forced to `'Intrusion'` regardless of what the IDS sends
- If `all_events = Yes`: the IDS must send `intrusion_normal` as either `'Intrusion'` or `'Normal'` (case-insensitive, ucfirst applied). Missing or invalid values return HTTP 401

### 3. Classification resolution
- IDS sends `classification` as a string (e.g. `'Alto'`)
- System looks up `classifications.description` and replaces it with `classifications_id`
- If `all_events = Yes` and event is `Normal`: classification is removed (Normal events have no risk classification)
- If classification string not found in DB: HTTP 401 error — event is rejected

### 4. Analysis creation
- IDS sends `analysys` as a free-text string
- System creates a new `Analysys` record with that text and stores `analysys_id` on the event
- Every event gets its own `Analysys` row — they are not reused

### 5. Type resolution
- IDS sends `type` as a string (e.g. `'SQL Injection'`)
- System looks up `types.description`; if not found, **creates a new Type record automatically**
- This means new threat types are created on the fly from IDS data — the types list can grow without admin intervention

### 6. Disabled attribute stripping
Fields corresponding to `event_attributes` records with `enabled = 0` are removed from the payload before insert. This prevents data from being stored in disabled custom columns.

### 7. DB insert with dynamic columns
The remaining payload is inserted directly. If an unknown column is sent, MySQL error 1054 is caught and returns HTTP 500 with a descriptive message.

### 8. Broadcast
After a successful insert, `EventCreated` is broadcast synchronously on `PrivateChannel('events')`.

---

## Event Query Rules

- `GET /api/v1/event` (index): returns today's events only, latest 100, ordered by `event_date_time DESC`. No pagination.
- `GET /api/v1/event/get/all`: paginated, filterable, with all relationships eager-loaded
- `GET /api/v1/event/{id}`: single event with all relationships
- `GET /api/v1/event/get/dashboards`: aggregated stats, filterable by date range and IDS agent

### Filter syntax (BaseController)
Query string format: `?filter=field:operator:value;field2:operator2:value2&filterDate=field:field_name;from:datetime;to:datetime`

---

## Dashboard Aggregations

`EventController::getDashboards()` returns:
- `totalEvents` — count in range
- `totalIntrusions` / `totalNormal` — count by intrusion_normal value
- `totalsByDay` — per-day breakdown with intrusion/normal split
- `classifications` — event count keyed by classification description
- `types` — event count keyed by type description, **intrusion events only**

All are filterable by `from`, `to` (datetime), and `ids` (IDS agent ID).

---

## Dynamic Event Attributes

Admins define custom fields that extend the `events` table schema at runtime.

Each `event_attribute` record has:
- `field_name` — the actual DB column name (validated: `[a-zA-Z_][a-zA-Z0-9_]*`)
- `display_value` — label shown in the UI
- `type_field` — `text` (VARCHAR 255) or `textarea` (TEXT)
- `show` — whether the field appears in the event detail view
- `enabled` — whether the field is accepted in ingest payloads; disabling also sets `show=0`
- `position` — display order

Creating an attribute adds a nullable column to `events`. Deleting it drops the column. **This is a destructive operation on production data.**

---

## Seeded Reference Data

This data is required for the application to function. It is created by `php artisan db:seed`.

**Profiles** (IDs matter — `profiles_id=1` is assumed to be Admin in `UsersSeeder`):
- ID 1: `Administrador`
- ID 2: `Usuário`

**Default admin user:**
- Email: `admin@yourcustomemail.com`
- Password: `@SmartDetector123@`
- `updated_pass = 0` (forced password change on first login)

**Classifications** (visual_style maps to Bootstrap color classes):
- `Baixo` → `success`
- `Médio` → `warning`
- `Alto` → `danger`

**Threat Types** (9 seeded, more created automatically at ingest):
Brute Force, Credential Stuffing, DDoS, DoS, DoS/DDoS, File Inclusion, Ransomware, SQL Injection, XSS

**Languages:** pt_BR, en, es, fr

---

## Translation System

Translations are split by domain. Each domain maps to a key in `resources/lang/{locale}/text.php`.

- Backend uses `__('text.key')` or `Lang::get('text.domain')`
- Frontend calls `GET /api/translation/{domain}` (public, no auth) at component mount
- Appending `__buttons` to a domain in the API call merges that domain's keys with the shared `buttons` domain: `GET /api/translation/users_domain__buttons`
- Locale priority: user's `app_locale` cookie → system_settings `select_language` → `pt_BR` default

---

## Incomplete / In-Progress Features

### LLM Integration (BETA)
- `LlmController` and the `llms` table are fully implemented
- `LlmController::analyzeIa()` makes the HTTP call to the LLM API but the return value is hardcoded and does not use the actual response
- LLM routes in `api.php` are entirely commented out — the LLM management UI (`/llm`) loads but all its API calls will fail
- `llm_standard` and `llm_prompt` system settings exist but are `active = 0` (hidden from UI)
- `use_smart_detector_ia` system setting is also `active = 0`

### Token Refresh
Frontend code in `bootstrap.js` attempts to refresh expired JWT tokens via `POST /api/refresh`, but this route does not exist. Expired sessions require a full re-login.

### AnalysysController
Exists with no routes defined. Likely scaffolded for future use.

---

## No Tests

The `tests/Feature/` and `tests/Unit/` directories contain only the default Laravel stub files (`ExampleTest.php`). There are no application-specific tests. Any changes to business logic, especially the event ingest pipeline and auth flow, carry full regression risk.
