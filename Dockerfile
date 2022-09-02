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

RUN composer install --optimize-autoloader

FROM php:zts-alpine

COPY --from=composer /workspace /app
WORKDIR /app

RUN apk add supervisor php81-pecl-redis php81-pecl-memcached php81-pecl-swoole
RUN mv /app/.env.example .env

RUN php /app/artisan key:generate
RUN php /app/artisan storage:link
RUN php /app/artisan optimize

COPY --from=git /workspace/docker/supervisord /etc/supervisor/conf.d
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /app/rr

ENTRYPOINT supervisord && php /app/artisan octane:start --port=60101
EXPOSE 60101
