<?php

namespace App\Http\Controllers\NGProxy;

use App\Exceptions\NGProxy\SongDisabledException;
use App\Exceptions\NGProxy\SongFetchException;
use App\Exceptions\NGProxy\SongProcessException;
use App\Exceptions\StorageContentMissingException;
use App\Http\Requests\NGProxy\SongGetRequest;
use App\Http\Traits\HasMessage;
use App\Services\NGProxy\SongService;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SongController extends Controller
{
    use HasMessage;

    public function __construct(
        protected SongService $service
    )
    {
    }

    /**
     * @throws SongDisabledException
     */
    public function info(int $id): array
    {
        return $this->fetch($id)->toArray();
    }

    /**
     * @throws SongDisabledException
     */
    protected function fetch(int $id)
    {
        try {
            return $this->service->find($id);
        } catch (SongFetchException|SongProcessException) {
            $this->pushErrorMessage(
                __('messages.song_fetch_failed')
            );
        }

        return back();
    }

    /**
     * @throws SongDisabledException
     */
    public function objectForGD(SongGetRequest $request): int|string
    {
        $data = $request->validated();
        return $this->object($data['songID']);
    }

    /**
     * @throws SongDisabledException
     */
    public function object(int $id): int|string
    {
        return $this->fetch($id)->object;
    }

    /**
     * @throws SongDisabledException
     * @throws StorageContentMissingException
     */
    public function download(int $id): StreamedResponse
    {
        return $this->fetch($id)->download();
    }
}
