<?php

namespace App\EndlessServer\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessServer\Requests\GameGetSongInfoRequest;

class GameSongController
{
	public function __construct(
		protected readonly NewgroundsAudioProxyService $newgroundsAudioProxyService
	)
	{

	}

	public function getInfo(GameGetSongInfoRequest $request): string
	{
		$data = $request->validated();

		return $this->newgroundsAudioProxyService->toObject(
			$this->newgroundsAudioProxyService->resolve($data['songID'])
		);
	}
}