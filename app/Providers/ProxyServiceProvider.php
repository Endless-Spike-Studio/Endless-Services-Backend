<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class ProxyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('proxy', function () {
            return Http::asForm()
                ->withOptions([
                    'proxy' => config('proxy.url'),
                ])->withUserAgent(null);
        });
    }
}
