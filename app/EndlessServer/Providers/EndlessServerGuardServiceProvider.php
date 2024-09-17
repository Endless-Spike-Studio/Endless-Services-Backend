<?php

namespace App\EndlessServer\Providers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Guards\AccountGuard;
use App\EndlessServer\Guards\PlayerGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class EndlessServerGuardServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		Auth::extend(EndlessServerAuthenticationGuards::ACCOUNT->value, function () {
			return app(AccountGuard::class);
		});

		Auth::extend(EndlessServerAuthenticationGuards::PLAYER->value, function () {
			return app(PlayerGuard::class);
		});
	}
}