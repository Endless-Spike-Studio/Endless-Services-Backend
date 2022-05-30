<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\CustomSong;
use Inertia\Inertia;
use Inertia\Response;

class CustomSongToolPresenter
{
    public function list(): Response
    {
        return Inertia::render('GDCS/Tools/Song/Custom/List', [
            'songs' => CustomSong::query()
                ->with('account:id,name')
                ->get(),
            'customSongOffset' => config('gdcs.custom_song_offset')
        ]);
    }
}
