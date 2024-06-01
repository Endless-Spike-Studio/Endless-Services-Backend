<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Exceptions\SongResolveException;
use App\EndlessProxy\Services\GeometryDashProxyService;
use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class GameApiProxyController
{
	public function __construct(
		protected readonly GeometryDashProxyService    $service,
		protected readonly NewgroundsAudioProxyService $audio
	)
	{

	}

	/**
	 * @throws ConnectionException
	 */
	public function handle(Request $request, string $path): string
	{
		$data = $request->all();

		return $this->service
			->getRequest()
			->post($path, $data);
	}

	/**
	 * @throws SongResolveException
	 */
	public function getSong(Request $request): string
	{
		$id = $request->integer('songID');

		if (empty($id)) {
			return '-1';
		}

		$song = $this->audio->resolve($id);
		return $this->audio->toObject($song);
	}
}