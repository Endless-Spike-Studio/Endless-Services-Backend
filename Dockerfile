FROM composer:latest

COPY . /app
WORKDIR /app

RUN composer install
RUN php /app/artisan storage:link