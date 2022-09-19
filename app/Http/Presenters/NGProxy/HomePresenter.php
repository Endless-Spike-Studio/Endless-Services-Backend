<?php

namespace App\Http\Presenters\NGProxy;

use App\Exceptions\NewGroundsProxyException;
use App\Services\NGProxy\SongService;
use Inertia\Inertia;
use Inertia\Response;

class HomePresenter
{
    /**
     * @throws NewGroundsProxyException
     */
    public function render(string $id): Response
    {
        if (is_numeric($id) && $id > 0) {
            $song = app(SongService::class)->find($id);
        }

        return Inertia::render('NGProxy/Home', [
            'song' => !empty($song) ? $song->toArray() : null
        ]);
    }
}
