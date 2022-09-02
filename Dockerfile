FROM node:alpine AS frontend

COPY --from=0 /workspace /app
WORKDIR /app

RUN npm install --global pnpm --loglevel silly
RUN pnpm install --loglevel silly

FROM composer AS composer

COPY --from=frontend /app /app
WORKDIR /app

RUN composer install -vvv --optimize-autoload

FROM php:zts-alpine

COPY --from=composer /app /app
WORKDIR /app

RUN apk add libmemcached-dev zlib-dev supervisor
RUN pecl install redis memcached swoole
RUN docker-php-ext-enable redis memcached swoole

RUN php /app/artisan key:generate
RUN php /app/artisan storage:link
RUN php /app/artisan optimize

COPY /app/docker/files/supervisord /etc/supervisor/conf.d
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /app/rr

ENTRYPOINT supervisord && php /app/artisan octane:start --port=60101
EXPOSE 60101
