<?php

namespace App\EndlessProxy\Controllers;

use App\Common\Responses\SuccessResponse;
use App\EndlessProxy\Channels\CallCounterChannel;
use App\EndlessProxy\Channels\NetworkLogChannel;
use App\EndlessProxy\Events\GameApiProxyCallCounterUpdateEvent;
use App\EndlessProxy\Events\GameApiProxyNetworkLogEvent;
use App\EndlessProxy\Exceptions\ProxyException;
use App\EndlessProxy\Services\GeometryDashProxyService;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

readonly class GameApiProxyController
{
	public function __construct(
		protected GeometryDashProxyService $service
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
				GameApiProxyNetworkLogEvent::broadcast($request->ip(), [
					'path' => $path,
					'data' => $data,
					'headers' => $response->headers(),
					'response' => $response->body()
				]);
			}

			GameApiProxyCallCounterUpdateEvent::dispatch(
				Cache::increment(
					$this->getCallCounterKey()
				)
			);

			return $response;
		} catch (HttpClientException $e) {
			throw new ProxyException('请求异常', previous: $e);
		}
	}

	protected function getCallCounterKey(): string
	{
		return sha1(
			implode('|', [
				__CLASS__, 'call_counter'
			])
		);
	}

	public function getCallCount(): SuccessResponse
	{
		return new SuccessResponse([
			'count' => intval(
				Cache::get(
					$this->getCallCounterKey()
				)
			)
		]);
	}

	public function getCallCounterWebsocketInfo(): SuccessResponse
	{
		return new SuccessResponse([
			'channel' => app(CallCounterChannel::class),
			'event' => GameApiProxyCallCounterUpdateEvent::class
		]);
	}

	public function getNetworkLogWebsocketInfo(): SuccessResponse
	{
		return new SuccessResponse([
			'enabled' => config('services.endless.proxy.network_log_enabled'),

			'channel' => app(NetworkLogChannel::class),
			'event' => GameApiProxyNetworkLogEvent::class
		]);
	}

	public function fetchNetworkLog(string $key): SuccessResponse
	{
		return new SuccessResponse(
			Cache::pull($key)
		);
	}
}