<?php

namespace App\Providers;

use App\Services\StorageService;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            'storage:gdcs.custom_song',
            fn () => StorageService::parseFromConfig('gdcs.storages.customSongData')
        );

        $this->app->bind(
            'storage:gdcs.level_data',
            fn () => StorageService::parseFromConfig('gdcs.storages.levelData')
        );

        $this->app->bind(
            'storage:gdcs.save_data',
            fn () => StorageService::parseFromConfig('gdcs.storages.saveData')
        );

        $this->app->bind(
            'storage:gdproxy.song_data',
            fn () => StorageService::parseFromConfig('gdproxy.storages.songData')
        );

        $this->app->bind(
            'storage:ngproxy.song_data',
            fn () => StorageService::parseFromConfig('ngproxy.storages.songData')
        );
    }
}
