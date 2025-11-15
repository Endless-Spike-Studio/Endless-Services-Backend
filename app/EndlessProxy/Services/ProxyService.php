<?php

namespace App\EndlessProxy\Services;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ProxyService
{
	public function getRequest(): PendingRequest
	{
		return Http::createPendingRequest()
			->withOptions([
				RequestOptions::PROXY => config('services.endless_proxy.server')
			])
			->retry(
				config('services.endless_proxy.retry.times'),
				config('services.endless_proxy.retry.delay')
			);
	}
}