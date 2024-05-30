<?php

namespace App\GeometryDashProxy\Services;

use App\GeometryDashProxy\Exceptions\RequestException;
use App\Proxy\Services\ProxyService as GlobalProxyService;
use Illuminate\Http\Client\ConnectionException;

class ProxyService
{
	/**
	 * @throws RequestException
	 */
	public function post(string $uri, array $data): string
	{
		try {
			return GlobalProxyService::instance()
				->asForm()
				->withUserAgent(null)
				->post(rtrim(config('gdcn.gdproxy.base'), '/') . $uri, $data)
				->body();
		} catch (ConnectionException $e) {
			throw new RequestException(
				previous: $e
			);
		}
	}
}