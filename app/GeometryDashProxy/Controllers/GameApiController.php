<?php

namespace App\GeometryDashProxy\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProxyService;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
	public function __construct(
		protected ProxyService $proxy
	)
	{

	}

	public function handle(Request $request): string
	{
		try {
			$data = $request->all();

			return ProxyService::instance()
				->withUserAgent(null)
				->asForm()
				->post(rtrim(config('gdcn.gdproxy.base'), '/') . $request->getRequestUri(), $data)
				->body();
		} catch (HttpClientException) {
			return -1;
		}
	}
}