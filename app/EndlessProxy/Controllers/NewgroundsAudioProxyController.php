<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessProxy\Services\NewgroundsAudioStorageService;
use App\EndlessProxy\Services\ProxyService;
use Symfony\Component\HttpFoundation\StreamedResponse;

readonly class NewgroundsAudioProxyController
{
	public function __construct(
		protected ProxyService                  $proxy,
		protected NewgroundsAudioProxyService   $service,
		protected NewgroundsAudioStorageService $storage
	)
	{

	}

	public function info(int $id): array
	{
		return $this->service->resolve($id)->toArray();
	}

	public function object(int $id): string
	{
		return $this->service->toObject(
			$this->service->resolve($id)
		);
	}

	public function download(int $id): StreamedResponse
	{
		$song = $this->service->resolve($id);

		$this->storage->song = $song;

		return $this->storage->download();
	}
}