<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ProxyService
{
	public static function instance(): PendingRequest
	{
		return Http::withOptions([
			'proxy' => config('gdcn.proxy.url')
		])->retry(3, 500);
	}
}