<?php

namespace App\Providers;

use App\Events\AccountRegistered;
use App\Events\LevelRated;
use App\Listeners\GDCS\ReCalculateCreatorPoints;
use App\Listeners\GDCS\SendVerificationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	protected $listen = [
		LevelRated::class => [
			ReCalculateCreatorPoints::class
		],
		AccountRegistered::class => [
			SendVerificationEmail::class
		]
	];
}
