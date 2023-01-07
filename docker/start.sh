cd /app
php artisan key:generate
php artisan optimize:clear
php artisan optimize
php artisan static:upload
php artisan manifest:sync

supervisord --nodaemon --configuration /_app/supervisord.conf
