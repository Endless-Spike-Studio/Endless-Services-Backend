FROM composer:latest AS builder

ADD . /app
RUN composer install --no-dev --optimize-autoloader --prefer-dist --ignore-platform-reqs

FROM registry.cn-shanghai.aliyuncs.com/gdcn/app-runtime:latest

COPY --from=builder /app /app
RUN mkdir /_

COPY docker/start.sh /_/start.sh
RUN chmod +x /_/start.sh

COPY docker/supervisord.conf /_/supervisord.conf
RUN php /app/artisan storage:link

ENTRYPOINT sh /_/start.sh
EXPOSE 60101
