FROM registry.cn-shanghai.aliyuncs.com/endless-spike-studio/runtime:v2

COPY . /app

RUN composer install --no-dev