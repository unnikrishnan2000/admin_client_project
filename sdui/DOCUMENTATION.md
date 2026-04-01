# SDUI Laravel Application Documentation

## Overview

This is a Laravel-based admin client project located in `sdui/` with an Inertia + Vue 3 frontend and Eloquent-powered backend. The application is designed to manage UI blocks and users through API endpoints and web pages.

## Key Modules

- `app/Models/UiBlock.php`: UI block model.
- `app/Models/User.php`: User model.
- `app/Http/Controllers/`: Application controllers that handle requests.
- `app/Http/Middleware/`: HTTP middleware layers for authorization and request checks.
- `resources/js/Pages/`: Vue 3 pages with Inertia-driven frontend.
- `routes/web.php`: Web routes for browser UI.
- `routes/auth.php`: Authentication routes.

## Frontend

- Uses Vite (`vite.config.js`) and Tailwind CSS (`tailwind.config.js`).
- Main JS entry point: `resources/js/app.js`.
- Vue pages: `resources/js/Pages/*.vue`.

## Backend

- Laravel default MVC structure.
- Database migrations: `database/migrations/` (including `create_ui_blocks_table` and `users` table).
- Seeder: `database/seeders/DatabaseSeeder.php`.

## Environment

- `.env` holds environment settings (database, app key, mail, etc.).
- `config/*.php` for app, auth, database, cache, etc.

## Routes

- `GET /` serves the main welcome/dashboard (Inertia view).
- `Auth` routes for login/registration (if enabled).
- Add API routes in `routes/api.php` if needed.

## Development Notes

- Use `php artisan serve` to start backend server.
- Use `npm run dev` for hot module reloading (Vue + CSS).
- For production builds, run `npm run build` and configure your webserver to serve from `public/`.

## Tests

- `tests/Feature` contains HTTP/feature tests.
- `tests/Unit` contains unit tests.
- Run tests with `./vendor/bin/phpunit`.
