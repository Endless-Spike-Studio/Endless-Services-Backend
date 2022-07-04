<?php

namespace App\Http\Presenters;

use App\Exceptions\NGProxy\SongDisabledException;
use App\Exceptions\NGProxy\SongGetFailedException;
use App\Http\Controllers\NGProxyController;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class NGProxyPresenter
{
    /**
     * @throws SongDisabledException
     * @throws SongGetFailedException
     */
    public function renderHomeWithSong(int $id): InertiaResponse
    {
        return Inertia::render('NGProxy/Home', [
            'song' => app(NGProxyController::class)->info($id)
        ]);
    }
}
