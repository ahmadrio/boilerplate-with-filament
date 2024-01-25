# Boilerplate with filament

## Preferences:

- PHP >= 8.1 (yang digunakan oleh docker php versi 8.3)
- Composer >= 2
- NodeJS >= 20
- Laravel Filament >= 3.x

## Setup:

- Run `composer install`
- Run `npm install`
- Copy file `.env.example` to `.env` & edit file `.env`
- Run `php artisan optimize`
- Run `php artisan migrate --seed`
- Run `php artisan serve`

## Commands:

| Lists                  | Description                        |
|------------------------|------------------------------------|
| `composer code-update` | update your code with laravel pint |
