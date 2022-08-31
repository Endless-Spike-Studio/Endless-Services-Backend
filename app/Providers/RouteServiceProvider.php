<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(
                    base_path('routes/api.php')
                );

            Route::middleware('web')
                ->group(
                    base_path('routes/web.php')
                );

            Route::middleware('game')
                ->group(
                    base_path('routes/game.php')
                );
        });
    }
}
