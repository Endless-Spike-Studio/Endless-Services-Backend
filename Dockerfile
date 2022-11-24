FROM composer:latest

WORKDIR /app
RUN git clone https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese /app
RUN composer install --no-dev --optimize-autoloader --prefer-dist --ignore-platform-reqs

FROM registry.cn-shanghai.aliyuncs.com/gdcn/app-runtime:latest

COPY --from=0 /app /app
COPY docker/supervisord.conf /etc/supervisord.conf
RUN php /app/artisan storage:link

ENTRYPOINT /usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf
EXPOSE 60101
