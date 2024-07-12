FROM registry.cn-shanghai.aliyuncs.com/endless-spike-studio/runtime:v2

COPY . /app
WORKDIR /app

RUN composer install --no-dev
RUN php /app/artisan storage:link