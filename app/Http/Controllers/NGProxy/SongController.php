<?php

namespace App\Http\Controllers\NGProxy;

use App\Exceptions\NewGroundsProxyException;
use App\Exceptions\StorageException;
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
     * @throws NewGroundsProxyException
     */
    public function info(int $id): array
    {
        return $this->service->find($id)->toArray();
    }

    /**
     * @throws NewGroundsProxyException
     */
    public function objectForGD(SongGetRequest $request): int|string
    {
        $data = $request->validated();
        return $this->object($data['songID']);
    }

    /**
     * @throws NewGroundsProxyException
     */
    public function object(int $id): int|string
    {
        return $this->service->find($id)->object;
    }

    /**
     * @throws NewGroundsProxyException
     * @throws StorageException
     */
    public function download(int $id): StreamedResponse
    {
        return $this->service->find($id)->download();
    }
}
