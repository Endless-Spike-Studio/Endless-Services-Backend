<?php

namespace App\Http\Presenters\GDProxy;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use Psr\Http\Client\ClientExceptionInterface;

class HomePresenter
{
    public function render(): Response
    {
        $data = [
            'uploadTotal' => 0,
            'downloadTotal' => 0
        ];

        try {
            $data = Http::asForm()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('gdcn.proxy.api.secret')
                ])
                ->get(config('gdcn.proxy.api.url') . '/connections')
                ->json();
        } catch (ClientExceptionInterface) {
            //
        }

        return Inertia::render('GDProxy/Home', [
            'count' => [
                'upload' => Arr::get($data, 'uploadTotal', 0),
                'download' => Arr::get($data, 'downloadTotal', 0)
            ]
        ]);
    }
}
