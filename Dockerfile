FROM registry.cn-shanghai.aliyuncs.com/gdcn/app-runtime:latest

RUN git clone https://ghproxy.com/https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese.git /app
WORKDIR /app

COPY docker/supervisord.conf /etc/supervisord.conf

RUN wget https://mirrors.aliyun.com/composer/composer.phar
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
RUN php /app/composer.phar install --no-dev --optimize-autoloader --prefer-dist
RUN rm -f /app/composer.phar

RUN php /app/artisan key:generate
RUN php /app/artisan storage:link
RUN php /app/artisan optimize

ENTRYPOINT /usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf
EXPOSE 60101
