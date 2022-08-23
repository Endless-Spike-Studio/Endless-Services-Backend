<?php

namespace App\Services\NGProxy;

use App\Exceptions\NGProxy\SongException;
use App\Exceptions\ResponseException;
use App\Models\NGProxy\Song;
use App\Services\Game\ResponseService;
use App\Services\Storage\SongStorageService;
use GeometryDashChinese\GeometryDashObject;
use Illuminate\Support\Arr;
use Psr\Http\Client\ClientExceptionInterface;

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
                throw new SongException(__('gdcn.song.error.fetch_failed_disabled'), http_code: 403);
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
        } catch (ResponseException) {
            $response = app('proxy')
                ->post(config('gdproxy.base_url') . '/getGJLevels21.php', [
                    'song' => $id,
                    'customSong' => true,
                    'secret' => 'Wmfd2893gb7',
                ])->body();

            try {
                ResponseService::check($response);
            } catch (ResponseException) {
                throw new SongException(__('gdcn.song.error.fetch_failed'));
            }

            $songObject = GeometryDashObject::split(Arr::get(explode('#', $response), 2), '~|~');
        }

        if (!Arr::has($songObject, [1, 2, 3, 4, 5, 10])) {
            throw new SongException(__('gdcn.song.error.fetch_failed_wrong_song_object'), log_context: [
                'object' => $songObject
            ]);
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
        if (!app(SongStorageService::class)->allValid(['id' => $song->song_id])) {
            try {
                $url = urldecode($song->original_download_url);

                $song->data = app('proxy')
                    ->withOptions(['decode_content' => false])
                    ->get($url)
                    ->body();
            } catch (ClientExceptionInterface $ex) {
                throw new SongException(
                    __('gdcn.song.error.process_failed_request_error', [
                        'reason' => $ex->getMessage()
                    ])
                );
            }
        }
    }
}
