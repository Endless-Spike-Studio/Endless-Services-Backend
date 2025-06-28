<?php

namespace App\EndlessProxy\Services;

use Illuminate\Http\Client\PendingRequest;

readonly class GeometryDashProxyService
{
	public function __construct(
		protected ProxyService $proxy
	)
	{

	}

	public function getRequest(): PendingRequest
	{
		return $this->proxy
			->getRequest()
			->asForm()
			->withUserAgent(false)
			->baseUrl(rtrim(config('services.endless_proxy.geometry_dash.base'), '/') . '/');
	}
}