<?php

namespace App\EndlessServices\Providers;

use App\EndlessServices\Enums\EndlessServicesAuthenticationGuards;
use App\EndlessServices\Extensions\Auth\Guards\EndlessUserGuard;
use App\EndlessServices\Extensions\Auth\Providers\EndlessUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class EndlessGuardServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		Auth::extend(EndlessServicesAuthenticationGuards::USER->value, function () {
			return app(EndlessUserGuard::class);
		});

		Auth::provider(EndlessServicesAuthenticationGuards::USER->value, function () {
			return app(EndlessUserProvider::class);
		});

		Config::set('auth.guards.' . EndlessServicesAuthenticationGuards::USER->value, [
			'driver' => EndlessServicesAuthenticationGuards::USER->value
		]);
	}
}