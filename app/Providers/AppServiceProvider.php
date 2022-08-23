<?php

namespace App\Providers;

use App\Services\Game\BaseGameService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Request::macro('context', function () {
            /** @var Request $this */

            return [
                'ip' => $this->ip(),
                'url' => $this->fullUrl(),
                'data' => collect(
                    $this->except(['gjp', 'password'])
                )->map(function (string $value) {
                    return Str::limit($value);
                })
            ];
        });

        BaseGameService::$perPage = config('gdcn.game.per_page', 10);
        Schema::defaultStringLength(191);
    }
}
