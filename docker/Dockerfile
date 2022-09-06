FROM registry-internal.cn-shanghai.aliyuncs.com/woshizhazha120/app-runtime:latest
WORKDIR /app

RUN git clone https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese.git

COPY files/supervisord.conf /etc/supervisord.conf
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /app/rr

RUN php /usr/bin/composer install --no-dev --optimize-autoloader --prefer-dist
RUN php /app/artisan storage:link

ENTRYPOINT /usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf
EXPOSE 60101
