<?php

namespace App\Providers;

use GeometryDashChinese\GeometryDashAlgorithm;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        GeometryDashAlgorithm::$perPage = config('gdcn.game.per_page', 10);
        Schema::defaultStringLength(191);
    }
}
