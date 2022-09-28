<?php

namespace App\Http\Presenters\GDCS\Tools;

use App\Models\GDCS\CustomSong;
use App\Models\GDCS\Level;
use Inertia\Inertia;
use Inertia\Response;

class SongCustomPresenter
{
    public function renderHome(): Response
    {
        $offset = config('gdcn.game.custom_song_offset');

        return Inertia::render('GDCS/Tools/Song/Custom/Home', [
            'offset' => $offset,
            'songs' => CustomSong::query()
                ->with(['account'])
                ->get()
                ->map(function (CustomSong $song) use ($offset) {
                    $usedCount = Level::query()
                        ->where('song_id', $song->id + $offset)
                        ->count();

                    return $song->setAttribute('used_count', $usedCount);
                })->toArray()
        ]);
    }
}
