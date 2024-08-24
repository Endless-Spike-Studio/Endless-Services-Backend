<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Exceptions\ProxyException;
use App\EndlessProxy\Services\GeometryDashProxyService;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;

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

			return $this->service
				->getRequest()
				->post($path, $data);
		} catch (HttpClientException $e) {
			throw new ProxyException('请求异常', previous: $e);
		}
	}
}