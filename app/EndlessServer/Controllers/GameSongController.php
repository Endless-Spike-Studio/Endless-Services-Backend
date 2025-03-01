<?php

namespace App\EndlessServer\Controllers;

use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Requests\GameFetchSongObjectRequest;
use App\EndlessServer\Requests\GameGetTopArtistsRequest;
use App\EndlessServer\Services\GamePaginationService;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashSongObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Illuminate\Support\Arr;

readonly class GameSongController
{
	public function __construct(
		protected NewgroundsAudioProxyService $newgroundsAudioProxyService,
		protected GamePaginationService       $paginationService,
		protected GeometryDashObjectService   $objectService
	)
	{

	}

	public function getTopArtists(GameGetTopArtistsRequest $request): string
	{
		$request->validated();

		$page = 1;

		if (isset($data['page'])) {
			$page = $data['page'];
		}

		// TODO
		$paginate = $this->paginationService->generate(Level::query()
			->whereHas('songMappings'), $page);

		return implode('#', [
			$paginate->items->map(function (Level $level) {
				$song = Arr::first($level->songMappings);

				if ($song === null) {
					return null;
				}

				return $this->objectService->merge([
					GeometryDashSongObjectDefinitions::ARTIST_NAME->value => $song->artist_name
				], ':');
			})->join('|'),
			$paginate->info()
		]);
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