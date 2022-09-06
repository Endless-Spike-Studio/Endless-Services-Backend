FROM registry.cn-shanghai.aliyuncs.com/woshizhazha120/app-runtime:latest
WORKDIR /app

RUN git clone https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese.git
RUN wget https://mirrors.aliyun.com/composer/composer.phar
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
RUN php /app/composer.phar install --no-dev --optimize-autoloader --prefer-dist
RUN php /app/artisan storage:link

COPY files/supervisord.conf /etc/supervisord.conf
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /app/rr

ENTRYPOINT /usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf
EXPOSE 60101
