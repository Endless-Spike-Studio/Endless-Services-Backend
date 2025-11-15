<?php

namespace App\EndlessProxyStatistic\Events;

use App\EndlessProxyStatistic\Channels\EndlessProxyStatisticChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EndlessProxyStatisticUpdatedEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public function __construct(
		protected int $newValue
	)
	{

	}

	public function broadcastOn()
	{
		return app(EndlessProxyStatisticChannel::class);
	}

	public function broadcastWith(): array
	{
		return [
			'count' => $this->newValue
		];
	}
}