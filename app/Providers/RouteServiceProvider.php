<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        RateLimiter::for('api', static function (Request $request) {
            $key = Auth::id() ?? $request->ip();
            return Limit::perMinute(60)->by($key);
        });

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
