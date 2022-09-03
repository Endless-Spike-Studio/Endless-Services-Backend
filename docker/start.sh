php /app/artisan optimize:clear
php /app/artisan storage:link
php /app/artisan key:generate
php /app/artisan migrate
php /app/artisan optimize
supervisord
php /app/artisan octane:start --port=60101
