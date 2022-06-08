<?php

namespace App\Http\Presenters\GDCS\Tools;

use App\Models\GDCS\CustomSong;
use Inertia\Inertia;
use Inertia\Response;

class CustomSongPresenter
{
    public function list(): Response
    {
        return Inertia::render('GDCS/Tools/Song/Custom/List', [
            'songs' => CustomSong::all()
                ?->load('account:id,name'),
            'customSongOffset' => config('gdcs.custom_song_offset')
        ]);
    }
}
