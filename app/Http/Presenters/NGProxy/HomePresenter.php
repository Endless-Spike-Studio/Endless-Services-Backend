<?php

namespace App\Http\Presenters\NGProxy;

use App\Exceptions\NewGroundsProxyException;
use App\Services\Game\SongService;
use Inertia\Inertia;
use Inertia\Response;

class HomePresenter
{
    /**
     * @throws NewGroundsProxyException
     */
    public function renderInfo(int $id): Response
    {
        try {
            $song = app(SongService::class)->find($id);
        } catch (NewGroundsProxyException $e) {
            if (empty($e->song)) {
                throw $e;
            }

            $song = $e->song;
        }

        return Inertia::render('NGProxy/Home', [
            'song' => $song->only(['song_id', 'name', 'artist_name', 'size', 'download_url'])
        ]);
    }
}
