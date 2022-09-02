FROM spiralscout/roadrunner:latest as roadrunner
FROM bitnami/git:latest AS git

RUN mkdir /workspace
RUN git clone https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese /workspace

FROM node:alpine AS frontend

COPY --from=git /workspace /workspace
WORKDIR /workspace

RUN npm install --global pnpm
RUN npx pnpm install

FROM composer AS composer

COPY --from=frontend /workspace /workspace
WORKDIR /workspace

RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-sockets

FROM php:latest

COPY --from=composer /workspace /app
WORKDIR /app

RUN apt-get update
RUN apt-get install -y libmemcached-dev zlib1g-dev
RUN pecl install redis memcached swoole

RUN apt-get install -y supervisor
RUN mv /app/.env.example .env

RUN php /app/artisan key:generate
RUN php /app/artisan storage:link
RUN php /app/artisan optimize

COPY --from=git /workspace/docker/supervisord /etc/supervisor/conf.d
COPY --from=roadrunner /usr/bin/rr /app/rr

RUN chmod +x /app/rr
RUN docker-php-ext-enable redis memcached swoole pdo pdo_mysql

ENTRYPOINT supervisord && php /app/artisan octane:start --port=60101
EXPOSE 60101
