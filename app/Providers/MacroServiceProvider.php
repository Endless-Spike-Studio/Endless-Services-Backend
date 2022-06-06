<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Arr::macro('rename', static function (&$arr, string $key, string $to) {
            if (array_key_exists($key, $arr)) {
                $arr[$to] = $arr[$key];
                unset($arr[$key]);
            }
        });
    }
}
