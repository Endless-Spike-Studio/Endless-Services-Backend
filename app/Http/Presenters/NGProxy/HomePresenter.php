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
    public function renderInfo(int $id): Response
    {
        $song = app(SongService::class)->find($id, true);

        return Inertia::render('NGProxy/Home', [
            'song' => $song->only(['song_id', 'name', 'artist_name', 'size', 'download_url'])
        ]);
    }
}
