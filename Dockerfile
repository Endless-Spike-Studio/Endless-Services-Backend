FROM registry.cn-shanghai.aliyuncs.com/gdcn/app-runtime:latest
COPY docker/supervisord.conf /etc/supervisord.conf

RUN git clone https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese /app
WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --prefer-dist --ignore-platform-reqs

RUN php /app/artisan storage:link
ENTRYPOINT /usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf
EXPOSE 60101
