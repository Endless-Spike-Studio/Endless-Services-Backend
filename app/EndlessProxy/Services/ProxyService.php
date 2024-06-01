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
				RequestOptions::PROXY => config('services.endless.proxy.server')
			])
			->retry(
				config('services.endless.proxy.retry.times'),
				config('services.endless.proxy.retry.delay')
			);
	}
}