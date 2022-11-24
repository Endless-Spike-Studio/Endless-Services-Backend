FROM composer:latest

WORKDIR /app
RUN git clone https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese /app
RUN composer install --no-dev --optimize-autoloader --prefer-dist --ignore-platform-reqs

FROM registry.cn-shanghai.aliyuncs.com/gdcn/app-runtime:latest

COPY --from=0 /app /app

COPY docker/start.sh /_app/start.sh
RUN chmox +x /_app/start.sh

COPY docker/supervisord.conf /_app/supervisord.conf
RUN php /app/artisan storage:link

ENTRYPOINT sh /_app/start.sh
EXPOSE 60101
