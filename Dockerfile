FROM node:alpine AS frontend
WORKDIR /workspace

RUN npm install --global pnpm
RUN pnpm install

FROM composer AS composer

COPY --from=frontend /workspace /workspace
WORKDIR /workspace

RUN composer install --optimize-autoload

FROM php:zts-alpine

COPY --from=composer /workspace /app
WORKDIR /app

RUN apk add libmemcached-dev zlib-dev supervisor
RUN pecl install redis memcached swoole
RUN docker-php-ext-enable redis memcached swoole

RUN php /app/artisan key:generate
RUN php /app/artisan storage:link
RUN php /app/artisan optimize

COPY /app/docker/supervisord /etc/supervisor/conf.d
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /app/rr

ENTRYPOINT supervisord && php /app/artisan octane:start --port=60101
EXPOSE 60101
