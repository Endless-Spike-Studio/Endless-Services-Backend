<?php

namespace App\Http\Presenters\NGProxy;

use App\Models\NGProxy\Song;
use Inertia\Inertia;
use Inertia\Response;

class HomePresenter
{
    public function render(?Song $song): Response
    {
        return Inertia::render('NGProxy/Home', [
            'song' => $song->exists ? $song->toArray() : null
        ]);
    }
}
