<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Requests\GameFetchSongObjectRequest;
use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\GeometryDash\Enums\GeometryDashResponses;

class GameSongApiProxyController
{
	public function __construct(
		protected readonly NewgroundsAudioProxyService $service
	)
	{

	}

	public function object(GameFetchSongObjectRequest $request): string
	{
		$data = $request->validated();

		$song = $this->service->resolve($data['songID']);

		if ($song->disabled) {
			return GeometryDashResponses::SONG_DISABLED->value;
		}

		return $this->service->toObject($song);
	}
}