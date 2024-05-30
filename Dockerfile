FROM composer

COPY . /app

RUN composer install --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs

FROM dunglas/frankenphp:latest-alpine

RUN install-php-extensions pcntl

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=0 /app /app

RUN php artisan config:cache
RUN php artisan event:cache
RUN php artisan route:cache

ENTRYPOINT php artisan octane:frankenphp