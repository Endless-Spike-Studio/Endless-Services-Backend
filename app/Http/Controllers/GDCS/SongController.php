<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\Response;
use App\Exceptions\NewGroundsProxyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\SongGetRequest;
use App\Http\Requests\GDCS\TopArtistFetchRequest;
use App\Models\GDCS\CustomSong;
use App\Services\NGProxy\SongService;

class SongController extends Controller
{
    public function fetch(SongGetRequest $request): int|string
    {
        $data = $request->validated();
        $customSongOffset = config('gdcs.custom_song_offset', 10000000);

        if ($data['songID'] >= $customSongOffset) {
            $song = CustomSong::query()
                ->where('id', $data['songID'] - $customSongOffset)
                ->first();

            if ($song === null) {
                return Response::SONG_NOT_FOUND->value;
            }

            return $song->object;
        }

        try {
            return app(SongService::class)
                ->find($data['songID'])
                ->object;
        } catch (NewGroundsProxyException) {
            return Response::SONG_FETCH_FAILED->value;
        }
    }

    public function fetchAllTopArtists(TopArtistFetchRequest $request): string
    {
        return app('proxy')
            ->post(config('gdproxy.base_url') . '/getGJTopArtists.php', $request->all())
            ->body();
    }
}
