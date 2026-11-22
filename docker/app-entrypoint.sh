#!/usr/bin/env bash
set -e

mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

if [ ! -f vendor/autoload.php ]; then
    echo "[entrypoint] Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --no-scripts --optimize-autoloader
fi

echo "[entrypoint] Waiting for PostgreSQL..."
until pg_isready -h "${DB_HOST:-testpostgres}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-db}"; do
    sleep 2
done

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entrypoint] Running database migrations..."
    php artisan migrate --force
fi

exec "$@"
