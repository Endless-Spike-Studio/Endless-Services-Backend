<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessProxy\Services\NewgroundsAudioStorageService;
use App\EndlessProxy\Services\ProxyService;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewgroundsAudioProxyController
{
	public function __construct(
		protected readonly ProxyService                  $proxy,
		protected readonly NewgroundsAudioProxyService   $service,
		protected readonly NewgroundsAudioStorageService $storage
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

		return Response::streamDownload(function () use ($song) {
			echo $this->storage->raw($song);
		}, $song->song_id . '.mp3');
	}
}