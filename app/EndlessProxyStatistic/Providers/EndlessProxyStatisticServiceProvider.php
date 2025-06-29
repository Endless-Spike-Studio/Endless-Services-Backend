<?php

namespace App\EndlessProxyStatistic\Providers;

use App\EndlessProxy\Events\EndlessProxyRequestHandledEvent;
use App\EndlessProxyStatistic\Listeners\EndlessProxyStatisticListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EndlessProxyStatisticServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		Event::listen(EndlessProxyRequestHandledEvent::class, EndlessProxyStatisticListener::class);
	}
}