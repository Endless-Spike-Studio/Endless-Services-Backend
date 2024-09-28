<?php

namespace App\EndlessProxy\Events;

use App\EndlessProxy\Channels\NetworkChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Cache;

class GameApiProxyNetworkEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, InteractsWithBroadcasting;

	protected string $key;

	public function __construct(
		readonly string $ip,
		readonly array  $data
	)
	{
		$this->key = sha1(
			implode('|', [
				__CLASS__, $ip, $data['path'], time()
			])
		);

		Cache::put($this->key, $data, now()->addHour());
	}

	public function broadcastOn(): Channel
	{
		return app(NetworkChannel::class);
	}

	public function broadcastWith(): array
	{
		return [
			'key' => $this->key
		];
	}
}