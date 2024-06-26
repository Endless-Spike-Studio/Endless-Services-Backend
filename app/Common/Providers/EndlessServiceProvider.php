<?php

namespace App\Common\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class EndlessServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		URL::forceRootUrl(
			config('app.url')
		);
	}
}