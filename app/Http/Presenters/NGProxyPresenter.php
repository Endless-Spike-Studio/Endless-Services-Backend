<?php

namespace App\Http\Presenters;

use App\Exceptions\NGProxy\SongException;
use App\Http\Controllers\NGProxy\SongController;
use App\Http\Traits\HasMessage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class NGProxyPresenter
{
    use HasMessage;

    /**
     * @throws SongException
     */
    public function renderHomeWithSong(int $id): InertiaResponse|RedirectResponse
    {
        return Inertia::render('NGProxy/Home', [
            'song' => app(SongController::class)->info($id),
        ]);
    }
}
