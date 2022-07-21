@servers(['web' => 'localhost'])

@include('vendor/autoload.php')

@setup
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
@endsetup

@before
Artisan::call('down');
@endbefore

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

@task('update-frontend')
pnpm run build
php artisan static:upload
@endtask

@task('update-backend')
php artisan migrate
php artisan optimize:clear
php artisan optimize
@endtask

@task('restart-server')
php artisan octane:reload
@endtask

@story('deploy-frontend')
update-code
update-frontend-packages
update-frontend
restart-server
@endstory

@story('deploy-backend')
update-code
update-backend-packages
update-backend
restart-server
@endstory

@story('deploy')
update-code
update-frontend-packages
update-backend-packages
update-frontend
update-backend
restart-server
@endstory

@after
Artisan::call('up');
@endafter
