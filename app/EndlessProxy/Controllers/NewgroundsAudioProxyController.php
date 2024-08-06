<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessProxy\Services\ProxyService;
use Illuminate\Support\Facades\Redirect;

class NewgroundsAudioProxyController
{
	public function __construct(
		protected readonly ProxyService                $proxy,
		protected readonly NewgroundsAudioProxyService $service
	)
	{

	}

	public function info(int $id): array
	{
		return $this->service->resolve($id)->toArray();
	}

	public function object(int $id): string
	{
		$song = $this->service->resolve($id);
		return $this->service->toObject($song);
	}

	public function download(int $id): string
	{
		$song = $this->service->resolve($id);
		$url = $this->service->url($song);

		return Redirect::to($url, 301);
	}
}