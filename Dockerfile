FROM composer:latest

COPY . /app

WORKDIR /app

RUN composer install --no-dev

FROM registry.cn-shanghai.aliyuncs.com/endless-spike-studio/runtime:v2

COPY --from=0 /app /app

WORKDIR /app

RUN php /app/artisan storage:link