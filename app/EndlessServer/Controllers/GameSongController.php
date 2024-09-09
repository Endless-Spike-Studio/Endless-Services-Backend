<?php

namespace App\EndlessServer\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessServer\Requests\GameFetchSongObjectRequest;
use App\GeometryDash\Enums\GeometryDashResponses;

class GameSongController
{
	public function __construct(
		protected readonly NewgroundsAudioProxyService $newgroundsAudioProxyService
	)
	{

	}

	public function getInfo(GameFetchSongObjectRequest $request): string
	{
		$data = $request->validated();

		$song = $this->newgroundsAudioProxyService->resolve($data['songID']);

		if ($song->disabled) {
			return GeometryDashResponses::SONG_DISABLED->value;
		}

		return $this->newgroundsAudioProxyService->toObject($song);
	}
}