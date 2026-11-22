# Courier API

A small Lumen-based REST API for managing couriers, backed by PostgreSQL.

## Requirements

- PHP 8.1+
- PostgreSQL 16+
- Composer

## Setup

```bash
cp .env.example .env
composer install
php artisan migrate
php artisan serve --host=0.0.0.0 --port=8000
```

Or with Docker:

```bash
make up
make migrate
```

## API

Base URL: `/api/v1`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/couriers` | List couriers (paginated) |
| GET | `/couriers/{id}` | Get a courier |
| POST | `/couriers` | Create a courier |
| PUT | `/couriers/{id}` | Update a courier |
| DELETE | `/couriers/{id}` | Delete a courier |

### List query parameters

- `name` – partial, case-insensitive name search
- `level` – comma-separated list of levels
- `order_by` – `id`, `name`, `level`, `created_at`
- `sort_by` – `asc` or `desc`
- `per_page` – page size (1–100, default 15)

### Health check

`GET /health`

## Testing

```bash
make run_ut
```
