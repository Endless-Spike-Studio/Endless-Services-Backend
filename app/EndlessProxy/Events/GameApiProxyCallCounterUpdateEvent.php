<?php

namespace App\EndlessProxy\Events;

use App\EndlessProxy\Channels\CallCounterChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class GameApiProxyCallCounterUpdateEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, InteractsWithBroadcasting;

	public function __construct(
		protected int $count
	)
	{

	}

	public function broadcastOn(): Channel
	{
		return app(CallCounterChannel::class);
	}

	public function broadcastWith(): array
	{
		return [
			'count' => $this->count
		];
	}
}