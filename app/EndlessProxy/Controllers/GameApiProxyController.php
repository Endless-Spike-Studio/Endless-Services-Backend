<?php

namespace App\EndlessProxy\Controllers;

use App\Api\Requests\ApiRequest;
use App\EndlessProxy\Events\EndlessProxyRequestHandledEvent;
use App\EndlessProxy\Exceptions\ProxyException;
use App\EndlessProxy\Services\GeometryDashProxyService;
use Illuminate\Http\Client\HttpClientException;

readonly class GameApiProxyController
{
	public function __construct(
		protected GeometryDashProxyService $service
	)
	{

	}

	public function handle(ApiRequest $request, string $path): string
	{
		try {
			$data = $request->all();

			$response = $this->service
				->getRequest()
				->post($path, $data);

			EndlessProxyRequestHandledEvent::dispatch($path, $request, $response);

			return $response;
		} catch (HttpClientException $e) {
			throw new ProxyException('请求异常', previous: $e);
		}
	}
}