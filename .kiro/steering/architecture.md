# Architecture

## Overview

Laravel 8 monolith with two distinct layers:

1. **REST API** (`/api/v1/`) — consumed by the Vue frontend and by external IDS agents
2. **Blade + Vue hybrid** — Blade serves thin shell pages that mount a single Vue component; all UI logic lives in Vue

The app is a **passive receiver**: IDS agents push events to the API, the backend processes and stores them, and the frontend renders them in real time via WebSockets.

---

## Request Lifecycle

### Web (browser)
```
Browser → Blade route (web.php) → Controller@show → returns view('page')
→ Vue component mounts → calls API via Axios → renders response
```

### API
```
IDS agent / Vue → POST /api/v1/... → jwt.auth middleware → [admin middleware] → Controller → BaseController → Model → DB
```

---

## Controller Hierarchy

```
Controller          (static JSON response helpers only)
  └── BaseController  (standard CRUD: index, store, update, destroy, paginate, filter)
        └── EventController
        └── UserController
        └── ClassificationController
        └── TypeController
        └── ProfileController
        └── EventAttributeController
        └── SystemSettingController
        └── IdsAgentController
        └── LlmController

AuthController      (extends Controller directly — login/logout/me)
TranslationsController  (no base class — translation-only, public endpoint)
AnalysysController  (extends BaseController — no routes, unused or future use)
```

All resource controllers inject their model via constructor: `parent::__construct($model)`.

**Validation rules live in the Model** (`rules()` and `feedback()` methods), not in request classes or controllers. `BaseController::store()` calls `$request->validate($this->model->rules(), $this->model->feedback())`.

---

## System Settings Config Injection

`SystemSettingsServiceProvider::boot()` runs on every request and:
1. Loads all rows from `system_settings` via `Cache::rememberForever('system_settings')`
2. Sets them into Laravel config: `Config::set('system_settings', $settings)`
3. Applies `timezone_selected` and `select_language` immediately

This means system settings are accessible anywhere as `config('system_settings.attribute_name')`. The cache is busted automatically by model hooks (`saved`/`deleted` on `system_setting`).

**If the `system_settings` table is empty or missing at boot, the provider catches the exception and logs it — the app will still start but settings-dependent features will silently fail.**

---

## Real-Time Event Flow

```
IDS agent → POST /api/v1/event
  → EventController::store()
  → [optional LLM analysis]
  → DB insert
  → event(new EventCreated($event))   ← ShouldBroadcastNow (synchronous, no queue)
  → PrivateChannel('events')
  → Laravel WebSockets server (port 6001)
  → Laravel Echo in browser
  → Home.vue::showNewEvent()
```

The broadcast is **synchronous** (`ShouldBroadcastNow`). There is no queue for broadcasting.

---

## Dynamic Event Schema

This is the most non-standard pattern in the codebase and requires care.

Admins can define custom fields via the Event Attributes UI. Creating an attribute **physically adds a column to the `events` table** at runtime. Deleting one **drops the column**.

- `EventAttributeController::store()` → calls `EventController::addTableColumn(field_name, type_field)`
- `EventAttributeController::destroy()` → calls `EventController::removeColumn(field_name)`
- `EventAttributeController::update()` → calls `EventController::updateColumn(type_field, field_name)`
- Column types: `text` → `VARCHAR(255)`, `textarea` → `TEXT`, both nullable
- `FieldNameValidator` validates field names with `/^[a-zA-Z_][a-zA-Z0-9_]*/` before any schema operation
- At event ingest, `EventAttributeController::getDisabledFields()` strips any disabled attribute fields from the payload before insert

**Never rename or drop columns in `events` migrations without checking `event_attributes` records first.**

---

## Frontend Architecture

- All Vue components are registered globally in `resources/js/app.js`
- Inter-component communication uses two mechanisms:
  - **Vuex store**: holds the currently-selected event (`store.state.item`) shared across modal/detail views
  - **EventBus** (`new Vue()` in `eventBus.js`): used for `loadList`, `setUrlFilter`, `paginate` events between Search/Paginate and list components
- All API calls must go through the helpers in `resources/js/utils/functions.js` (`axiosGet`, `axiosPost`, `axiosPatch`, `axiosDelete`) — not raw `axios` calls (exception: `Home.vue` calls `axios.get` directly, which is a known inconsistency)
- `API_URL` in `utils/functions.js` is hardcoded to `http://localhost:8000` — this must be updated for non-local environments

---

## Route Organization

- `routes/api.php` — all API routes under `/api/v1/`, JWT-protected by default
- `routes/web.php` — Blade view routes, session-auth protected
- Web route slugs are in Portuguese (`/eventos`, `/usuarios`, `/classificacoes-de-risco`)
- API routes use English resource names (`/api/v1/event`, `/api/v1/user`)
- LLM API routes are **commented out** in `api.php`

---

## Rate Limiting

Configured in `RouteServiceProvider::configureRateLimiting()`:

- `api` limiter: 60 req/min per user/IP (all JWT routes)
- `store-events` limiter: configurable via `system_settings.request_per_minute` (default 1000, max 5000) — applied only to `POST /api/v1/event`
