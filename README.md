# Ecommerce-Project

A Laravel 10 e‑commerce application scaffold with secure authentication, event-driven order processing, modular code organization.

---

## Features

- Secure authentication using Laravel Fortify: login, registration, password reset, email verification, and optional TOTP two‑factor authentication (with recovery codes).
- Admin guard support and per-guard views/routes (admin routes are handled under `admin/*`).
- API authentication using Laravel Sanctum for token-based access (mobile/web API clients).
- Domain model for e‑commerce: Products, Categories, Stores, Tags, Cart, Orders, OrderItems, OrderAddress, and Admin/User models.
- Event-driven order processing (Events & Listeners) to decouple inventory deduction, notifications and background jobs.
- Observers for model lifecycle automation and Repository pattern for testable business logic.
 - Notifications using multiple channels: email, database (stored notifications), and Pusher (broadcast) for realtime alerts — implemented via Laravel's Notification system.
 - Soft deletes support on applicable models so records can be restored after accidental deletion.
 - Server-side validation using Form Requests and validators to ensure data integrity.
- Third-party currency conversion helper (configurable API key/url) for multi-currency support.
- Paginated product listings and admin tables using Laravel pagination.
- Asset pipeline with Vite + Tailwind CSS.
- Migrations, seeders and `storage:link` ready for reproducible local setup.

---

## Tech Stack

- PHP 8.1+, Laravel 10
- Laravel Fortify (auth) and Sanctum (API tokens)
- Vite, Tailwind CSS, npm
- MySQL 

---

## Requirements

- PHP ^8.1 with required extensions
- Composer
- Node.js & npm
- Database server (MySQL, MariaDB, SQLite, PostgreSQL)
- A local web server (Laragon recommended on Windows) or Docker / Sail

---

## Quick Setup (Windows PowerShell)

Open PowerShell in the project root (`c:\laragon\www\Ecommerce-Project`) and run:

```powershell
# 1. Install PHP dependencies
composer install

# 2. Copy .env and edit values
copy .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Configure DB and other .env values (see example below)

# 5. Run database migrations and seeders
php artisan migrate --seed

# 6. Link storage for public files
php artisan storage:link

# 7. Install frontend dependencies and build
npm install
npm run dev   # for development watch
npm run build # for production assets

# 8. Run application
php artisan serve --host=127.0.0.1 --port=8000
```

Notes:
- If you use Laragon, point your virtual host to the `public` folder for nicer URLs.
- For Docker/Sail users, prefer Sail commands (`./vendor/bin/sail up`) instead of `php artisan serve`.

---

## Working with Two‑Factor Authentication

- Two‑factor is enabled in `config/fortify.php`. By default, this project configures Fortify to require a confirmation step when enabling 2FA (`'confirm' => true`).
- To change behavior (challenge users immediately when `two_factor_secret` exists), set `'confirm' => false` in `config/fortify.php`.
- The two-factor challenge view is registered at `resources/views/front/auth/two-factor-challenge.blade.php`.

---

## API & Sanctum

- The project includes Laravel Sanctum for API token authentication. Use `sanctum` middleware for protected routes.
- Typical flow for issuing a token (example in a controller):

```php
$token = $user->createToken('mobile')->plainTextToken;
```

- For SPA usage, configure `SANCTUM_STATEFUL_DOMAINS` and use cookie-based authentication per Laravel docs.

---

## Currency Conversion

- Currency conversion logic is available in `app/Helpers/Currency.php`. Configure `CURRENCY_API_URL` and `CURRENCY_API_KEY` in your `.env` to enable third‑party conversion.
- The helper fetches rates from the configured provider and can be used in controllers and views to display converted prices.

---

## Pagination

- Product and list endpoints use Laravel's `paginate()` method to return paginated results. Update pagination size in controllers or use a config/env value to control default page size.

---

