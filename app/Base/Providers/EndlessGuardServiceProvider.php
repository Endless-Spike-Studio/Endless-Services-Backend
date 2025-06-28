<?php

namespace App\Base\Providers;

use App\Base\Extensions\Auth\Guards\EndlessUserGuard;
use App\Base\Extensions\Auth\Providers\EndlessUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class EndlessGuardServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		Auth::extend('endless_services', function () {
			return app(EndlessUserGuard::class);
		});

		Auth::provider('endless_services', function () {
			return app(EndlessUserProvider::class);
		});

		Config::set('auth.guards.endless_services', [
			'driver' => 'endless_services'
		]);
	}
}