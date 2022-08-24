<?php

namespace App\Providers;

use App\Services\Game\BaseGameService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Request::macro('context', function () {
            /** @var Request $this */

            $parameters = [];
            foreach ($this->all() as $key => $value) {
                if (in_array($key, ['gjp', 'password'], true)) {
                    $length = strlen($value);
                    $parameters[$key] = str_repeat('*', $length);
                    continue;
                }

                $parameters[$key] = $value;
            }

            return [
                'ip' => $this->ip(),
                'url' => $this->fullUrl(),
                'data' => $parameters
            ];
        });

        BaseGameService::$perPage = config('gdcn.game.per_page', 10);
        Schema::defaultStringLength(191);
    }
}
