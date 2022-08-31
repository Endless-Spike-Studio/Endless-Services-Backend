<?php

namespace App\Providers;

use App\Services\Game\BaseGameService;
use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        BaseGameService::$perPage = config('gdcn.game.per_page', 10);
    }
}
