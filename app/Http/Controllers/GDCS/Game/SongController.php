<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Objects\SongObject;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\SongFetchRequest;
use App\Http\Requests\GDCS\Game\TopArtistFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\CustomSong;
use App\Models\GDCS\Level;
use App\NewgroundsProxy\Exceptions\NewgroundsProxyException;
use App\Services\Game\BaseGameService;
use App\Services\Game\CustomSongService;
use App\Services\Game\ObjectService;
use App\Services\Game\SongService;

class SongController extends Controller
{
	use GameLog;

	/**
	 * @throws GeometryDashChineseServerException
	 */
	public function fetch(SongFetchRequest $request): string
	{
		try {
			$data = $request->validated();

			if ($data['songID'] >= CustomSongService::$offset) {
				$song = CustomSong::query()
					->find($data['songID'] - CustomSongService::$offset);

				if (!$song) {
					throw new GeometryDashChineseServerException(__('gdcn.game.error.song_fetch_failed_not_found_custom'), gameResponse: Response::GAME_SONG_FETCH_FAILED_NOT_FOUND_CUSTOM->value);
				}
			} else {
				$song = (new SongService)->find($data['songID']);
			}

			$this->logGame(__('gdcn.game.action.song_fetch_success'));
			return $song->object;
		} catch (NewgroundsProxyException $e) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.song_fetch_failed_upstream_exception'), previous: $e, gameResponse: $e->gameResponse);
		}
	}

	public function fetchTopArtists(TopArtistFetchRequest $request): string
	{
		$data = $request->validated();

		$query = Level::query()
			->selectRaw('song_id, count(*) as times')
			->where('song_id', '<', CustomSongService::$offset)
			->where('song_id', '!=', 0)
			->groupBy('song_id')
			->orderByDesc('times');

		$this->logGame(__('gdcn.game.action.featured_artists_fetch_success'));
		return $query->forPage(++$data['page'], BaseGameService::$perPage)
			->get()
			->map(function (Level $level) {
				return ObjectService::merge([
					SongObject::ARTIST_NAME => $level->song->artist_name
				], ':');
			})->join('|');
	}
}
