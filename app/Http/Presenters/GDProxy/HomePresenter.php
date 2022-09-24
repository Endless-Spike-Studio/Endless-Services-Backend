<?php

namespace App\Http\Presenters\GDProxy;

use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class HomePresenter
{
    public function render(): Response
    {
        $data = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('gdcn.proxy.api.secret')
            ])
            ->get(config('gdcn.proxy.api.url') . '/connections')
            ->json();

        return Inertia::render('GDProxy/Home', [
            'count' => [
                'upload' => $data['uploadTotal'],
                'download' => $data['downloadTotal']
            ]
        ]);
    }
}
