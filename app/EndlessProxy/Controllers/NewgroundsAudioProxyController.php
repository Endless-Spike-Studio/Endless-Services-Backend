<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Exceptions\SongResolveException;
use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessProxy\Services\ProxyService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewgroundsAudioProxyController
{
	public function __construct(
		protected readonly ProxyService                $proxy,
		protected readonly NewgroundsAudioProxyService $service
	)
	{

	}

	/**
	 * @throws SongResolveException
	 * @throws ConnectionException
	 */
	public function info(int $id): array
	{
		return $this->service->resolve($id)->toArray();
	}

	/**
	 * @throws SongResolveException
	 * @throws ConnectionException
	 */
	public function object(int $id): string
	{
		$song = $this->service->resolve($id);
		return $this->service->toObject($song);
	}

	/**
	 * @throws SongResolveException
	 * @throws ConnectionException
	 */
	public function download(int $id): StreamedResponse
	{
		$song = $this->service->resolve($id);
		$data = $this->service->toData($song);

		return Response::streamDownload(function () use ($data) {
			echo $data;
		}, "$song->song_id.mp3");
	}
}