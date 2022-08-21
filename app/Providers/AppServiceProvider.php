<?php

namespace App\Providers;

use App\Services\Game\BaseGameService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        BaseGameService::$perPage = config('gdcn.game.per_page', 10);
        Schema::defaultStringLength(191);
    }
}
