FROM ghcr.io/Endless-Spike-Studio/Endless-Services-Runtime

COPY . /app
WORKDIR /app

RUN composer install --no-dev
RUN php /app/artisan storage:link