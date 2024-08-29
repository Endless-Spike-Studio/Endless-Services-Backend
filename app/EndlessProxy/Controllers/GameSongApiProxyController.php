<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use Illuminate\Http\Request;

class GameSongApiProxyController
{
	public function __construct(
		protected readonly NewgroundsAudioProxyService $service
	)
	{

	}

	public function object(Request $request): string
	{
		$id = $request->integer('songID');

		if (empty($id)) {
			return '-1';
		}

		return $this->service->toObject(
			$this->service->resolve($id)
		);
	}
}