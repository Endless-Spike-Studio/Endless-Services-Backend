<?php

namespace App\Services\NGProxy;

use App\Exceptions\NGProxy\SongException;
use App\Exceptions\ResponseException;
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

            $this->process($song);
            return $song;
        }

        try {
            $response = app('proxy')
                ->post(config('gdproxy.base_url') . '/getGJSongInfo.php', [
                    'songID' => $id,
                    'secret' => 'Wmfd2893gb7',
                ])->body();

            ResponseService::check($response);
            $songObject = GeometryDashObject::split($response, '~|~');
        } catch (ResponseException $e) {
            $response = app('proxy')
                ->post(config('gdproxy.base_url') . '/getGJLevels21.php', [
                    'song' => $id,
                    'customSong' => true,
                    'secret' => 'Wmfd2893gb7',
                ])->body();

            try {
                ResponseService::check($response);
            } catch (ResponseException) {
                $e = SongException::notFound();
                $e->log_context = ['result' => $response];

                throw $e;
            }

            $songObject = GeometryDashObject::split(Arr::get(explode('#', $response), 2), '~|~');
        }

        if (!Arr::has($songObject, [1, 2, 3, 4, 5, 10])) {
            $e = SongException::processing();
            $e->log_context = ['error' => '歌曲对象处理失败', 'id' => $id, 'result' => $songObject];

            throw $e;
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

        $this->process($song);
        return $song;
    }

    /**
     * @throws SongException
     */
    protected function process(Song $song): void
    {
        if (!app(SongStorageService::class)->allExists(['id' => $song->song_id])) {
            try {
                $url = urldecode($song->original_download_url);

                $song->data = app('proxy')
                    ->withOptions(['decode_content' => false,])
                    ->get($url)
                    ->body();
            } catch (GuzzleException $ex) {
                $e = SongException::processing();
                $e->log_context = ['error' => '请求失败', 'message' => $ex->getMessage(), 'url' => $url, 'id' => $song->song_id];

                throw $e;
            }
        }
    }
}
