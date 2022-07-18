<?php

namespace App\Http\Presenters;

use App\Exceptions\NGProxy\SongDisabledException;
use App\Exceptions\NGProxy\SongFetchException;
use App\Http\Controllers\NGProxy\SongController;
use App\Http\Traits\HasMessage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class NGProxyPresenter
{
    use HasMessage;

    public function renderHomeWithSong(int $id): InertiaResponse|RedirectResponse
    {
        try {
            return Inertia::render('NGProxy/Home', [
                'song' => app(SongController::class)->info($id),
            ]);
        } catch (SongFetchException) {
            $this->pushErrorMessage(
                __('messages.song_not_found')
            );

            return back();
        } catch (SongDisabledException) {
            $this->pushErrorMessage(
                __('messages.song_disabled')
            );

            return back();
        }
    }
}
