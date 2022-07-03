@servers(['localhost' => '127.0.0.1'])

@task('update')
cd /app
git pull
composer install --no-dev
php artisan optimize:clear
php artisan migrate
pnpm install
pnpm run build
php artisan static:upload
php artisan optimize
php artisan octane:reload
@endtask
