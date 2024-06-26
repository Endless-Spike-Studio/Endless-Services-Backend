<?php

namespace App\Common\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class EndlessServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		if (App::isProduction()) {
			URL::forceScheme('https');
			URL::forceRootUrl(
				config('app.url')
			);
		}
	}
}