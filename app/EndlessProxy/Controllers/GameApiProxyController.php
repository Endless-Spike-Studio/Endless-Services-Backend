<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Channels\NetworkChannel;
use App\EndlessProxy\Events\GameApiProxyNetworkEvent;
use App\EndlessProxy\Exceptions\ProxyException;
use App\EndlessProxy\Services\GeometryDashProxyService;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class GameApiProxyController
{
	public function __construct(
		protected readonly GeometryDashProxyService $service
	)
	{

	}

	public function handle(Request $request, string $path): string
	{
		try {
			$data = $request->all();

			$response = $this->service
				->getRequest()
				->post($path, $data);

			if (config('services.endless.proxy.network_log_enabled')) {
				GameApiProxyNetworkEvent::broadcast($request->ip(), [
					'path' => $path,
					'data' => $data,
					'headers' => $response->headers(),
					'response' => $response->body()
				]);
			}

			return $response;
		} catch (HttpClientException $e) {
			throw new ProxyException('请求异常', previous: $e);
		}
	}

	public function getNetworkWebsocketInfo(): array
	{
		return [
			'broadcaster' => 'reverb',
			'key' => config('broadcasting.connections.reverb.key'),
			'wsHost' => config('websocket.host'),
			'wsPath' => config('websocket.path'),
			'wsPort' => config('websocket.ws_port'),
			'wssPort' => config('websocket.wss_port'),
			'forceTLS' => false,
			'enabledTransports' => ['ws', 'wss'],
			'authEndpoint' => URL::action([BroadcastController::class, 'authenticate']),

			'channel' => app(NetworkChannel::class),
			'event' => GameApiProxyNetworkEvent::class
		];
	}

	public function pullNetwork(string $key)
	{
		return Cache::pull($key);
	}
}