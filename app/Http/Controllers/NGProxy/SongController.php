<?php

namespace App\Http\Controllers\NGProxy;

use App\Exceptions\NGProxy\SongDisabledException;
use App\Exceptions\NGProxy\SongFetchException;
use App\Exceptions\NGProxy\SongProcessException;
use App\Exceptions\StorageContentMissingException;
use App\Http\Requests\NGProxy\SongGetRequest;
use App\Services\NGProxy\SongService;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SongController extends Controller
{
    public function __construct(
        protected SongService $service
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
        return $this->service
            ->find($id)
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

        return $this->service
            ->find($data['songID'])
            ->object;
    }

    /**
     * @throws SongDisabledException
     * @throws SongProcessException
     * @throws SongFetchException
     */
    public function object(int $id): int|string
    {
        return $this->service
            ->find($id)
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
        return $this->service
            ->find($id)
            ->download();
    }
}
