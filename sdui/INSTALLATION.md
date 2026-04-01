# SDUI Laravel Application Installation

## Prerequisites

- PHP 8.1+ installed
- Composer installed
- Node.js 18+ and npm (or Yarn) installed
- MySQL or compatible database

## Clone repository

```bash
git clone <your-repo-url> sdui
cd sdui
```

## Install PHP dependencies

```bash
composer install
```

## Install JS dependencies

```bash
npm install
# or yarn
```

## Environment configuration

1. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

2. Set database and other settings in `.env` (e.g., `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, etc.)

3. Generate app key:

```bash
php artisan key:generate
```

## Database setup

```bash
php artisan migrate
php artisan db:seed
```

## Build assets (development)

```bash
npm run dev
```

## Build assets (production)

```bash
npm run build
```

## Serve app

```bash
php artisan serve
```

Visit `http://localhost:8000`.

## Optional

- Run tests:

```bash
./vendor/bin/phpunit
```

- Lint PHP with Pint:

```bash
./vendor/bin/pint
```
