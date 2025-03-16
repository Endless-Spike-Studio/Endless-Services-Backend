<?php

namespace App\EndlessServer\Controllers;

use App\EndlessProxy\Models\NewgroundsSong;
use App\EndlessProxy\Objects\GameSongObject;
use App\EndlessProxy\Services\NewgroundsAudioProxyService;
use App\EndlessServer\Models\LevelSongMapping;
use App\EndlessServer\Requests\GameFetchSongObjectRequest;
use App\EndlessServer\Requests\GameGetTopArtistsRequest;
use App\EndlessServer\Services\GamePaginationService;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashSongObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Illuminate\Support\Facades\DB;

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

		$songMappings = LevelSongMapping::query()
			->select([
				DB::raw('DISTINCT(newgrounds_song_id)'),
				DB::raw('COUNT(newgrounds_song_id) AS use_count')
			])
			->groupBy('newgrounds_song_id')
			->orderByDesc('use_count')
			->get();


		$songMappings->each(function (LevelSongMapping $mapping) {
			return $this->newgroundsAudioProxyService->resolve($mapping->newgrounds_song_id);
		});

		$songIds = $songMappings->map(function (LevelSongMapping $mapping) {
			return $mapping->newgrounds_song_id;
		});

		$paginate = $this->paginationService->generate(NewgroundsSong::query()
			->whereIn('song_id', $songIds), $page);

		return implode('#', [
			$paginate->items->map(function (NewgroundsSong $song) {
				return new GameSongObject($song)->only([
					GeometryDashSongObjectDefinitions::ARTIST_NAME->value,
					GeometryDashSongObjectDefinitions::YOUTUBE_URL->value
				])->merge(':');
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