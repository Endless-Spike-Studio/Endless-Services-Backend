FROM dunglas/frankenphp:alpine

ENV SERVER_NAME=0.0.0.0:80

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN install-php-extensions pdo_pgsql opcache memcached redis

ADD . /app