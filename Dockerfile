FROM spiralscout/roadrunner AS roadrunner
FROM node:alpine AS frontend

WORKDIR /workspace
RUN npm install --global pnpm --loglevel silly
RUN pnpm install --loglevel silly

FROM composer AS composer

WORKDIR /workspace
RUN composer install -vvv --optimize-autoload

FROM php:zts-alpine

RUN mv /workspace /app
RUN apk add libmemcached-dev zlib-dev supervisor
RUN pecl install redis memcached swoole
RUN docker-php-ext-enable redis memcached swoole

RUN php /app/artisan key:generate
RUN php /app/artisan storage:link
RUN php /app/artisan optimize

COPY /workspace/docker/files/supervisord /etc/supervisor/conf.d
COPY --from=roadrunner /usr/bin/rr /app/rr

ENTRYPOINT supervisord && php artisan octane:start --port=60101
EXPOSE 60101
