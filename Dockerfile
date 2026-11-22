FROM php:8.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        libonig-dev \
        unzip \
        git \
        postgresql-client \
    && docker-php-ext-install pdo_pgsql pgsql zip mbstring sockets \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
COPY database/ database/

RUN composer install \
        --no-interaction \
        --prefer-dist \
        --no-dev \
        --no-scripts \
        --no-progress \
        --optimize-autoloader

COPY . .

COPY docker/app-entrypoint.sh /usr/local/bin/app-entrypoint
RUN chmod +x /usr/local/bin/app-entrypoint

EXPOSE 9000

ENTRYPOINT ["app-entrypoint"]
CMD ["php-fpm"]
