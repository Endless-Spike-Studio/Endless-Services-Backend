<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessProxy\Services\ProxyService;
use Illuminate\Support\Facades\Response;

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

		return Response::streamDownload(function () use ($song) {
			echo $this->service->raw($song);
		}, $song->song_id . '.mp3');
	}
}