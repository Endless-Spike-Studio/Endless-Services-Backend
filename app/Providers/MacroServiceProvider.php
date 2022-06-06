<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Arr::macro('rename', static function (array $arr, array $changes) {
            foreach ($changes as $key => $to) {
                if (Arr::has($arr, $key)) {
                    $arr[$to] = $arr[$key];
                    unset($arr[$key]);
                }
            }

            return $arr;
        });
    }
}
