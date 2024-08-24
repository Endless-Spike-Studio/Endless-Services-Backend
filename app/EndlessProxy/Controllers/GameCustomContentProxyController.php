<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Exceptions\CustomContentResolveException;
use App\EndlessProxy\Services\GeometryDashProxyService;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class GameCustomContentProxyController
{
	public function __construct(
		protected readonly GeometryDashProxyService $proxy
	)
	{

	}

	public function base(): string
	{
		return URL::action([__CLASS__, 'handle'], '/');
	}

	public function handle(Request $request, string $path): string
	{
		try {
			$data = $request->all();

			$upstream = Cache::rememberForever(sha1(
				implode('|', [__CLASS__, __FUNCTION__, 'upstream'])
			), function () {
				return $this->proxy
					->getRequest()
					->post('/getCustomContentURL.php')
					->body();
			});

			return $this->proxy
				->getRequest()
				->baseUrl($upstream)
				->get($path, $data)
				->body();
		} catch (HttpClientException $e) {
			throw new CustomContentResolveException('请求异常', previous: $e);
		}
	}
}