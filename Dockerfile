FROM composer
WORKDIR /workspace

RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-sockets

FROM php:zts-alpine

COPY --from=0 /workspace /app
WORKDIR /app

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories
RUN apk add --update --no-cache libmemcached-dev ${PHPIZE_DEPS}
RUN pecl install redis memcached swoole

RUN apt-get install -y supervisor
RUN mv /app/.env.example .env

RUN php /app/artisan key:generate
RUN php /app/artisan storage:link
RUN php /app/artisan optimize

COPY --from=0 /workspace/docker/start.sh /app/start.sh
COPY --from=0 /workspace/docker/supervisord /etc/supervisor/conf.d
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /app/rr

RUN chmod +x /app/rr
RUN chmod +x /app/start.sh

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable redis memcached swoole

ENTRYPOINT sh /app/start.sh
EXPOSE 60101
