<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidResponseException;
use App\Exceptions\NGProxy\SongDisabledException;
use App\Exceptions\NGProxy\SongGetFailedException;
use App\Exceptions\NGProxy\SongNotFoundException;
use App\Exceptions\StorageContentMissingException;
use App\Http\Requests\NGProxy\SongGetRequest;
use App\Jobs\NGProxy\ProcessSongJob;
use App\Models\NGProxy\Song;
use App\Services\StorageService;
use GDCN\GDObject\GDObject;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function app;
use function config;

class NGProxyController extends Controller
{
    /**
     * @throws SongGetFailedException
     * @throws SongDisabledException
     */
    public function info(int $id): array
    {
        $song = $this->getSong($id);

        return [
            ...$song->toArray(),
            'download_url' => $song->download_url
        ];
    }

    /**
     * @throws SongGetFailedException
     * @throws SongDisabledException
     */
    protected function getSong(int $songID): Song
    {
        try {
            return $this->tryGetFromDatabase($songID);
        } catch (SongNotFoundException) {
            $songObject = $this->tryGetFromGDAPI($songID);

            $song = Song::query()
                ->create([
                    'song_id' => $songObject[1],
                    'name' => $songObject[2],
                    'artist_id' => $songObject[3],
                    'artist_name' => $songObject[4],
                    'size' => $songObject[5],
                    'disabled' => false,
                    'original_download_url' => $songObject[10]
                ]);

            ProcessSongJob::dispatchSync($songID, $songObject[10]);
            return $song;
        }
    }

    /**
     * @throws SongNotFoundException
     * @throws SongDisabledException
     * @throws SongGetFailedException
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

            $songDownloadUrl = $song->original_download_url ?? Arr::get($this->tryGetFromGDAPI($id), 10);
            ProcessSongJob::dispatchSync($id, $songDownloadUrl);

            return $song;
        }

        throw new SongNotFoundException();
    }

    /**
     * @throws SongGetFailedException
     */
    protected function tryGetFromGDAPI(int $id): array
    {
        try {
            return $this->tryGetFromGDSongInfoAPI($id);
        } catch (InvalidResponseException|SongDisabledException) {
            try {
                return $this->tryGetFromGDLevelAPI($id);
            } catch (InvalidResponseException) {
                throw new SongGetFailedException();
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
                    'secret' => 'Wmfd2893gb7'
                ])->body();

            HelperController::checkResponse($response);
            return GDObject::split($response, '~|~');
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
                'secret' => 'Wmfd2893gb7'
            ])->body();

        HelperController::checkResponse($response);
        return GDObject::split(Arr::get(explode('#', $response), 2), '~|~');
    }

    /**
     * @throws SongDisabledException
     * @throws SongGetFailedException
     */
    public function objectForGD(SongGetRequest $request): int|string
    {
        $data = $request->validated();
        return $this->object($data['songID']);
    }

    /**
     * @throws SongDisabledException
     * @throws SongGetFailedException
     */
    public function object(int $id): int|string
    {
        return $this->getSong($id)->object;
    }

    /**
     * @throws SongGetFailedException
     */
    public function download(int $id): StreamedResponse
    {
        try {
            /** @var StorageService $storage */
            $storage = app('storage:ngproxy.song_data');
            return $storage->download($id);
        } catch (StorageContentMissingException) {
            $songDownloadUrl = Arr::get($this->tryGetFromGDAPI($id), 10);
            ProcessSongJob::dispatchSync($id, $songDownloadUrl);

            return $this->download($id);
        }
    }
}
