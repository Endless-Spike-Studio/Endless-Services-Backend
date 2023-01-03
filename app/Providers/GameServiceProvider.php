<?php

namespace App\Providers;

use App\Services\Game\BaseGameService;
use App\Services\Game\CustomSongService;
use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        BaseGameService::$perPage = config('gdcn.game.per_page', 10);
        CustomSongService::$offset = config('gdcn.game.custom_song_offset', 10000000);
    }
}
