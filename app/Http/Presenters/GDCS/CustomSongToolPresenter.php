<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\CustomSong;
use App\Services\Game\CustomSongService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomSongToolPresenter
{
    public function renderIndex(): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Song/Custom/Home', [
            'offset' => CustomSongService::$offset,
            'currentAccountID' => $account->id,
            'songs' => function () {
                $id = Request::get('id');

                $query = CustomSong::query()
                    ->select(['id', 'account_id', 'name', 'artist_name', 'size', 'download_url', 'created_at'])
                    ->with(['account:id,name']);

                if (!empty($id)) {
                    $query->where('id', $id - CustomSongService::$offset);
                }

                return $query->paginate();
            }
        ]);
    }

    public function renderUploaded(): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Song/Custom/Home', [
            'offset' => CustomSongService::$offset,
            'currentAccountID' => $account->id,
            'songs' => $account->uploadedCustomSongs()
                ->select(['id', 'account_id', 'name', 'artist_name', 'size', 'download_url', 'created_at'])
                ->with(['account:id,name'])
                ->paginate()
        ]);
    }
}
