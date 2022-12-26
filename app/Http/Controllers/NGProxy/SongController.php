<?php

namespace App\Http\Controllers\NGProxy;

use App\Exceptions\NewGroundsProxyException;
use App\Http\Requests\NGProxy\SongGetRequest;
use App\Http\Traits\HasMessage;
use App\Services\NGProxy\SongService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

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
        return $this->service->find($id, true)->toArray();
    }

    /**
     * @throws NewGroundsProxyException
     */
    public function objectForGame(SongGetRequest $request): int|string
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
     */
    public function download(int $id): RedirectResponse
    {
        return $this->service->find($id)->download();
    }
}
