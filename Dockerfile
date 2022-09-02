FROM spiralscout/roadrunner AS roadrunner
FROM bitnami/git:latest AS git

RUN mkdir /app
RUN git clone https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese /app --verbose

FROM node:alpine AS frontend

COPY --from=git /app /app/
RUN npm install --global pnpm --loglevel silly
RUN pnpm install --loglevel silly

FROM composer AS composer

COPY --from=frontend /app /app/
RUN composer install -vvv --optimize-autoload

COPY files/supervisord /etc/supervisor/conf.d
COPY --from=roadrunner /usr/bin/rr /app/rr

FROM php:zts-alpine

RUN apk add libmemcached-dev zlib-dev
RUN pecl install redis memcached swoole
RUN docker-php-ext-enable redis memcached swoole

RUN php /app/artisan key:generate
RUN php /app/artisan storage:link
RUN php /app/artisan optimize

COPY files/supervisord /etc/supervisor/conf.d
COPY --from=roadrunner /usr/bin/rr /app/rr

ENTRYPOINT supervisord && php artisan octane:start --port=60101
EXPOSE 60101
