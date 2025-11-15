<?php

namespace App\EndlessProxyStatistic\Listeners;

use App\EndlessProxyStatistic\Events\EndlessProxyStatisticUpdatedEvent;
use Illuminate\Support\Facades\Cache;

readonly class EndlessProxyStatisticListener
{
	public function handle(): void
	{
		$count = Cache::increment('endless_proxy:statistic:counter');

		EndlessProxyStatisticUpdatedEvent::dispatch($count);
	}
}