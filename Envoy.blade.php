@servers(['web' => 'localhost'])

@task('update-code')
git fetch --all
git reset --hard origin/main
git pull
@endtask

@task('update-frontend-packages')
pnpm update
@endtask

@task('update-backend-packages')
composer update --no-dev
@endtask

@story('update-packages)
pnpm update
@endstory

@task('deploy-frontend')
pnpm run build
php artisan static:upload
@endtask

@task('deploy-backend')
php artisan migrate
php artisan optimize:clear
php artisan optimize
@endtask

@task('restart-server')
php artisan octane:reload
@endtask

@story('deploy-frontend')
update-code
update-frontend-dependencies
deploy-frontend
restart-server
@endstory

@story('deploy-backend')
update-code
update-backend-dependencies
deploy-backend
restart-server
@endstory

@story('deploy')
update-code
update-dependencies
deploy-frontend
deploy-backend
restart-server
@endstory

