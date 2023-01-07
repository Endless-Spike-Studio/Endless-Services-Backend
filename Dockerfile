FROM composer:latest AS builder-backend

ADD . /app
WORKDIR /app
RUN composer install --no-dev --optimize-autoloader --prefer-dist --ignore-platform-reqs

FROM node:current-alpine AS builder-frontend

COPY --from=builder-backend /app /app
WORKDIR /app

RUN npm install --global pnpm
RUN pnpm install
RUN pnpm run build

FROM registry.cn-shanghai.aliyuncs.com/gdcn/app-runtime:latest

COPY --from=builder-frontend /app /app
WORKDIR /app
RUN mkdir /_

COPY docker/start.sh /_/start.sh
RUN chmod +x /_/start.sh

COPY docker/supervisord.conf /_/supervisord.conf
RUN php /app/artisan storage:link

ENTRYPOINT sh /_/start.sh
EXPOSE 60101
