<?php

namespace App\Http\Presenters\GDCN\Dashboard;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserPresenter
{
    public function renderProfile(Request $request): Response
    {
        return Inertia::render('GDCN/User/Profile', [
            'gdcn' => [
                'user' => $request->user()
                    ?->select(['id', 'name', 'email', 'email_verified_at', 'created_at'])
                    ?->firstOrFail()
            ]
        ]);
    }

    public function renderSetting(Request $request): Response
    {
        return Inertia::render('GDCN/User/Setting', [
            'gdcn' => [
                'user' => $request->user()
                    ?->select(['id', 'name', 'email'])
                    ?->firstOrFail()
            ]
        ]);
    }
}
