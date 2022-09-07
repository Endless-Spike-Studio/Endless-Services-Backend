@servers(['web' => 'localhost'])

@task('clean-logs')
rm -f storage/logs/*.log
rm -rf storage/logs/product
@endtask

@task('update-code')
git fetch --all
git reset --hard origin/main
git pull
@endtask

@task('update-backend-packages')
composer update --no-dev
@endtask

@task('update-backend')
php artisan migrate
php artisan optimize:clear
php artisan optimize
@endtask

@task('restart-server')
php artisan octane:reload
@endtask

@story('deploy-backend')
update-code
update-backend-packages
update-backend
restart-server
@endstory

@story('deploy-backend-without-package-update')
update-code
update-backend
restart-server
@endstory
