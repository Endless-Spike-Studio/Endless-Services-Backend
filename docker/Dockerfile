FROM registry.cn-shanghai.aliyuncs.com/woshizhazha120/app-runtime:latest

COPY files/supervisord.conf /etc/supervisord.conf
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /app/rr

RUN wget https://mirrors.aliyun.com/composer/composer.phar
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

RUN php /app/composer.phar install --no-dev --optimize-autoloader --prefer-dist
RUN php /app/artisan storage:link

RUN chmod +x /app/rr
RUN rm -f /var/cache/apk/*

ENTRYPOINT /usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf
EXPOSE 60101
