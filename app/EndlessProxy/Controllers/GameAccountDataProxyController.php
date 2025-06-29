<?php

namespace App\EndlessProxy\Controllers;

use App\Api\Requests\ApiRequest;
use App\EndlessProxy\Exceptions\ProxyException;
use App\EndlessProxy\Requests\GameFetchAccountServerRequest;
use App\EndlessProxy\Services\GeometryDashProxyService;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Random\Randomizer;

readonly class GameAccountDataProxyController
{
	public function __construct(
		protected GeometryDashProxyService $proxy
	)
	{

	}

	public function base(GameFetchAccountServerRequest $request): string
	{
		$data = $request->validated();

		return URL::action([__CLASS__, 'handle'], [
			'accountID' => $data['accountID'],
			'type' => $data['type'],
			'path' => '/'
		]);
	}

	public function handle(ApiRequest $request, string $path): string
	{
		try {
			$data = $request->all();

			$upstream = Cache::rememberForever(sha1(
				implode('|', [__CLASS__, __FUNCTION__, 'upstream'])
			), function () {
				return $this->proxy
					->getRequest()
					->post('/getAccountURL.php', [
						'type' => (new Randomizer)->getInt(1, 2),
						'secret' => GeometryDashSecrets::COMMON->value
					])
					->body();
			});

			return $this->proxy
				->getRequest()
				->baseUrl($upstream)
				->post($path, $data)
				->body();
		} catch (HttpClientException $e) {
			throw new ProxyException('请求异常', previous: $e);
		}
	}
}