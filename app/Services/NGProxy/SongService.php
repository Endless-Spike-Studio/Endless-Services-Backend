<?php

namespace App\Services\NGProxy;

use App\Exceptions\InvalidResponseException;
use App\Exceptions\NGProxy\SongDisabledException;
use App\Exceptions\NGProxy\SongFetchException;
use App\Exceptions\NGProxy\SongNotFoundException;
use App\Exceptions\NGProxy\SongProcessException;
use App\Http\Controllers\HelperController;
use App\Models\NGProxy\Song;
use App\Services\StorageService;
use GeometryDashChinese\GeometryDashObject;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class SongService
{
    /**
     * @throws SongDisabledException
     * @throws SongFetchException
     * @throws SongProcessException
     */
    public function find(int $id): Song
    {
        try {
            return $this->tryGetFromDatabase($id);
        } catch (SongNotFoundException) {
            $songObject = $this->tryGetFromGDAPI($id);

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


            if (!$this->processSong($song)) {
                throw SongProcessException::failed();
            }

            return $song;
        }
    }

    /**
     * @throws SongNotFoundException
     * @throws SongDisabledException
     * @throws SongFetchException
     */
    protected function tryGetFromDatabase(int $id): Song
    {
        $song = Song::query()
            ->where('song_id', $id)
            ->first();

        if (!empty($song)) {
            if ($song->disabled) {
                throw new SongDisabledException();
            }

            if (!$this->processSong($song)) {
                throw SongProcessException::failed();
            }

            return $song;
        }

        throw new SongNotFoundException();
    }

    /**
     * @throws SongFetchException
     */
    protected function processSong(Song $song): bool
    {
        /** @var StorageService $storage */
        $storage = app('storage:ngproxy.song_data');

        if (!$storage->allExists($song->song_id)) {
            $url = urldecode($song->original_download_url) ?? Arr::get($this->tryGetFromGDAPI($song->song_id), 10);

            try {
                $data = app('proxy')
                    ->withOptions([
                        'decode_content' => false,
                    ])
                    ->get($url)
                    ->body();
            } catch (GuzzleException $e) {
                return false;
            }

            $storage->put($song->song_id, $data);
        }

        return true;
    }

    /**
     * @throws SongFetchException
     */
    protected function tryGetFromGDAPI(int $id): array
    {
        try {
            return $this->tryGetFromGDSongInfoAPI($id);
        } catch (InvalidResponseException|SongDisabledException) {
            try {
                return $this->tryGetFromGDLevelAPI($id);
            } catch (InvalidResponseException) {
                throw new SongFetchException();
            }
        }
    }

    /**
     * @throws SongDisabledException
     * @throws InvalidResponseException
     */
    protected function tryGetFromGDSongInfoAPI(int $songID): array
    {
        try {
            $response = app('proxy')
                ->post(config('gdproxy.base_url') . '/getGJSongInfo.php', [
                    'songID' => $songID,
                    'secret' => 'Wmfd2893gb7',
                ])->body();

            HelperController::checkResponse($response);
            return GeometryDashObject::split($response, '~|~');
        } catch (InvalidResponseException $e) {
            if (!empty($response) && $response === '-2') {
                throw new SongDisabledException();
            }

            throw $e;
        }
    }

    /**
     * @throws InvalidResponseException
     */
    protected function tryGetFromGDLevelAPI(int $id): array
    {
        $response = app('proxy')
            ->post(config('gdproxy.base_url') . '/getGJLevels21.php', [
                'song' => $id,
                'customSong' => true,
                'secret' => 'Wmfd2893gb7',
            ])->body();

        HelperController::checkResponse($response);
        return GeometryDashObject::split(Arr::get(explode('#', $response), 2), '~|~');
    }
}
