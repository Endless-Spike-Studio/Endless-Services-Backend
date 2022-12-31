<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\CustomSong;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CustomSongToolPresenter
{
    public function renderIndex(): Response
    {
        return Inertia::render('GDCS/Tools/Song/Custom/Home', [
            'offset' => config('gdcn.game.custom_song_offset'),
            'songs' => CustomSong::query()
                ->select(['id', 'account_id', 'name', 'artist_name', 'size', 'download_url', 'created_at'])
                ->with(['account:id,name'])
                ->paginate()
        ]);
    }

    public function renderUploaded(): Response
    {
        return Inertia::render('GDCS/Tools/Song/Custom/Home', [
            'offset' => config('gdcn.game.custom_song_offset'),
            'songs' => Auth::guard('gdcs')
                ->user()
                ->uploadedCustomSongs()
                ->select(['id', 'account_id', 'name', 'artist_name', 'size', 'download_url', 'created_at'])
                ->with(['account:id,name'])
                ->paginate()
        ]);
    }
}
