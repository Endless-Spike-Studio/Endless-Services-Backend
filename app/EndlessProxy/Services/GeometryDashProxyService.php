<?php

namespace App\EndlessProxy\Services;

use Illuminate\Http\Client\PendingRequest;

class GeometryDashProxyService
{
	public function __construct(
		protected readonly ProxyService $proxy
	)
	{

	}

	public function getRequest(): PendingRequest
	{
		return $this->proxy
			->getRequest()
			->asForm()
			->withUserAgent(false)
			->baseUrl(rtrim(config('services.endless.proxy.geometry_dash.base'), '/') . '/');
	}
}