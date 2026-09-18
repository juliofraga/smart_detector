# Tech Stack

## Backend
- **Language**: PHP 7.3+
- **Framework**: Laravel 8.x
- **Auth**: JWT via `tymon/jwt-auth` v1.0.2
- **Real-time**: `beyondcode/laravel-websockets` + Pusher
- **Database**: MySQL / MariaDB (Eloquent ORM)
- **HTTP Client**: Guzzle 7
- **Testing**: PHPUnit 9.3

## Frontend
- **Framework**: Vue.js 2.6 (Options API)
- **State**: Vuex 3.x
- **UI**: Bootstrap 5
- **Charts**: Chart.js 4
- **HTTP**: Axios 0.21
- **Build**: Laravel Mix 6 (Webpack wrapper)
- **Utilities**: jQuery 3.6, Lodash 4

## Configuration
- Default timezone: `America/Sao_Paulo`
- Default locale: `pt_BR`
- API base URL: `http://localhost:8000` (set in `resources/js/utils/functions.js`)

---

## Common Commands

### Backend
```bash
php artisan serve                          # Start dev server (port 8000)
php artisan migrate                        # Run migrations
php artisan db:seed                        # Seed database
php artisan db:seed --class=LanguagesSeeder
php artisan tinker                         # REPL
php artisan key:generate                   # Generate APP_KEY
php artisan websockets:serve               # Start WebSocket server
./vendor/bin/phpunit                       # Run tests
```

### Frontend
```bash
npm install          # Install dependencies
npm run dev          # Build (development)
npm run watch        # Watch mode
npm run hot          # Hot module reload
npm run prod         # Build (production, minified)
```

### Scaffolding
```bash
php artisan make:model ModelName -m        # Model + migration
php artisan make:controller ControllerName
php artisan make:migration migration_name
```
