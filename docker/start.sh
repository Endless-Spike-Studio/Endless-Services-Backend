cd /app
php artisan key:generate
php artisan optimize:clear && php artisan optimize

supervisord --nodaemon --configuration /_/supervisord.conf
