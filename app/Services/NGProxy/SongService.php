<?php

namespace App\Services\NGProxy;

use App\Exceptions\NGProxy\SongException;
use App\Models\NGProxy\Song;
use App\Services\Game\ResponseService;
use App\Services\Storage\SongStorageService;
use GeometryDashChinese\GeometryDashObject;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class SongService
{
    /**
     * @throws SongException
     */
    public function find(int $id): Song
    {
        $song = Song::query()
            ->where('song_id', $id)
            ->first();

        if (!empty($song)) {
            if ($song->disabled) {
                throw SongException::disabled();
            }

            $this->processSong($song);
            return $song;
        }

        $response = app('proxy')
            ->post(config('gdproxy.base_url') . '/getGJSongInfo.php', [
                'songID' => $id,
                'secret' => 'Wmfd2893gb7',
            ])->body();

        if (ResponseService::check($response)) {
            $songObject = GeometryDashObject::split($response, '~|~');
        } else {
            $response = app('proxy')
                ->post(config('gdproxy.base_url') . '/getGJLevels21.php', [
                    'song' => $id,
                    'customSong' => true,
                    'secret' => 'Wmfd2893gb7',
                ])->body();

            if (!ResponseService::check($response)) {
                throw SongException::notFound();
            }

            $songObject = GeometryDashObject::split(Arr::get(explode('#', $response), 2), '~|~');
        }

        if (!Arr::has($songObject, [1, 2, 3, 4, 5, 10])) {
            throw SongException::processing();
        }

        $song = Song::query()
            ->create([
                'song_id' => $songObject[1],
                'name' => $songObject[2],
                'artist_id' => $songObject[3],
                'artist_name' => $songObject[4],
                'size' => $songObject[5],
                'disabled' => false,
                'original_download_url' => $songObject[10],
            ]);

        $this->processSong($song);
        return $song;
    }

    /**
     * @throws SongException
     */
    protected function processSong(Song $song): void
    {
        if (!app(SongStorageService::class)->allExists($song->song_id)) {
            try {
                $url = urldecode($song->original_download_url);

                $song->data = app('proxy')
                    ->withOptions(['decode_content' => false,])
                    ->get($url)
                    ->body();

                $song->save();
            } catch (GuzzleException) {
                throw SongException::processing();
            }
        }
    }
}
