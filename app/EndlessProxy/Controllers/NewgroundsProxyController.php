<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Exceptions\SongResolveException;
use App\EndlessProxy\Services\NewgroundsProxyService;
use App\EndlessProxy\Services\ProxyService;
use Illuminate\Http\Client\ConnectionException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewgroundsProxyController
{
	public function __construct(
		protected readonly ProxyService           $proxy,
		protected readonly NewgroundsProxyService $service
	)
	{

	}

	/**
	 * @throws SongResolveException
	 */
	public function info(int $id): array
	{
		return $this->service->resolve($id)->toArray();
	}

	/**
	 * @throws SongResolveException
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

		return new StreamedResponse(function () use ($data) {
			echo $data;
		}, headers: [
			'Content-Disposition' => 'attachment; filename=' . $song->song_id . '.mp3'
		]);
	}
}