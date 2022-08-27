<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Objects\SongObject;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Exceptions\NewGroundsProxyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\SongFetchRequest;
use App\Http\Requests\GDCS\Game\TopArtistFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\CustomSong;
use App\Models\GDCS\Level;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;
use App\Services\NGProxy\SongService;

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
            $customSongOffset = config('gdcn.game.custom_song_offset', 10000000);

            if ($data['songID'] >= $customSongOffset) {
                $song = CustomSong::query()
                    ->find($data['songID'] - $customSongOffset);

                if (!$song) {
                    throw new GeometryDashChineseServerException(__('gdcn.game.error.song_fetch_failed_not_found_custom'), game_response: Response::GAME_SONG_FETCH_FAILED_NOT_FOUND_CUSTOM->value);
                }
            } else {
                $song = app(SongService::class)
                    ->find($data['songID']);
            }

            $this->logGame(__('gdcn.game.action.song_fetch_success'));
            return $song->object;
        } catch (NewGroundsProxyException $e) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.song_fetch_failed_upstream_exception'), previous: $e, game_response: $e->game_response);
        }
    }

    public function fetchTopArtists(TopArtistFetchRequest $request): string
    {
        $data = $request->validated();
        $customSongOffset = config('gdcn.game.custom_song_offset', 10000000);

        $query = Level::query()
            ->selectRaw('song_id, count(*) as times')
            ->where('song_id', '<', $customSongOffset)
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
