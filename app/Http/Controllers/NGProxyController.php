<?php

namespace App\Http\Controllers;

use App\Exceptions\NGProxy\SongDisabledException;
use App\Exceptions\NGProxy\SongFetchException;
use App\Exceptions\NGProxy\SongProcessException;
use App\Exceptions\StorageContentMissingException;
use App\Http\Requests\NGProxy\SongGetRequest;
use App\Services\NGProxy\SongService;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NGProxyController extends Controller
{
    public function __construct(
        protected SongService $songService
    )
    {
    }

    /**
     * @throws SongDisabledException
     * @throws SongProcessException
     * @throws SongFetchException
     */
    public function info(int $id): array
    {
        return $this->songService
            ->fetchSong($id)
            ->toArray();
    }

    /**
     * @throws SongDisabledException
     * @throws SongProcessException
     * @throws SongFetchException
     */
    public function objectForGD(SongGetRequest $request): int|string
    {
        $data = $request->validated();

        return $this->songService
            ->fetchSong($data['songID'])
            ->object;
    }

    /**
     * @throws SongDisabledException
     * @throws SongProcessException
     * @throws SongFetchException
     */
    public function object(int $id): int|string
    {
        return $this->songService
            ->fetchSong($id)
            ->object;
    }

    /**
     * @throws SongDisabledException
     * @throws SongProcessException
     * @throws SongFetchException
     * @throws StorageContentMissingException
     */
    public function download(int $id): StreamedResponse
    {
        return $this->songService
            ->fetchSong($id)
            ->download();
    }
}
