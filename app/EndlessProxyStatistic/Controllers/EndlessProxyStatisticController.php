<?php

namespace App\EndlessProxyStatistic\Controllers;

use App\Api\Responses\SuccessResponse;
use App\EndlessProxyStatistic\Channels\EndlessProxyStatisticChannel;
use App\EndlessProxyStatistic\Events\EndlessProxyStatisticUpdatedEvent;
use Illuminate\Support\Facades\Cache;

class EndlessProxyStatisticController
{
	public function getWebsocketInfo(): SuccessResponse
	{
		return new SuccessResponse([
			'channel' => app(EndlessProxyStatisticChannel::class),
			'event' => EndlessProxyStatisticUpdatedEvent::class
		]);
	}

	public function dispatchInitialEvent(): void
	{
		$count = Cache::get('endless_proxy:statistic:counter', 0);

		EndlessProxyStatisticUpdatedEvent::dispatch($count);
	}
}