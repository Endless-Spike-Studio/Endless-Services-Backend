FROM registry.cn-shanghai.aliyuncs.com/woshizhazha120/app-runtime:latest

RUN cp -rf /tmp /app
WORKDIR /app

COPY files/supervisord.conf /etc/supervisord.conf
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /app/rr

RUN php /usr/bin/composer install --no-dev --optimize-autoloader --prefer-dist
RUN php /app/artisan storage:link

ENTRYPOINT /usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf
EXPOSE 60101
