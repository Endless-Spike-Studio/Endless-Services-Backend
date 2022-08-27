<?php

namespace App\Http\Presenters;

use App\Exceptions\NewGroundsProxyException;
use App\Http\Traits\HasMessage;
use App\Services\NGProxy\SongService;
use App\Services\Storage\SongStorageService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class NGProxyPresenter
{
    use HasMessage;

    /**
     * @throws NewGroundsProxyException
     */
    public function renderHomeWithSong(int $id): InertiaResponse|RedirectResponse
    {
        $item = app(SongService::class)->find($id);

        return Inertia::render('NGProxy/Home', [
            'song' => $item->toArray(),
            'ready' => app(SongStorageService::class)->exists(['id' => $item->song_id])
        ]);
    }
}
