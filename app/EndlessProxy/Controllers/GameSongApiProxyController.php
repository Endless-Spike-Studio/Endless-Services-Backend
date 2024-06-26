<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use Illuminate\Http\Request;

class GameSongApiProxyController
{
	public function __construct(
		protected readonly NewgroundsAudioProxyService $audio
	)
	{

	}

	public function object(Request $request): string
	{
		$id = $request->integer('songID');

		if (empty($id)) {
			return '-1';
		}

		$song = $this->audio->resolve($id);
		return $this->audio->toObject($song);
	}
}