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
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Song/Custom/Home', [
            'offset' => config('gdcn.game.custom_song_offset'),
            'currentAccountID' => $account->id,
            'songs' => CustomSong::query()
                ->select(['id', 'account_id', 'name', 'artist_name', 'size', 'download_url', 'created_at'])
                ->with(['account:id,name'])
                ->paginate()
        ]);
    }

    public function renderUploaded(): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Song/Custom/Home', [
            'offset' => config('gdcn.game.custom_song_offset'),
            'currentAccountID' => $account->id,
            'songs' => $account->uploadedCustomSongs()
                ->select(['id', 'account_id', 'name', 'artist_name', 'size', 'download_url', 'created_at'])
                ->with(['account:id,name'])
                ->paginate()
        ]);
    }
}
