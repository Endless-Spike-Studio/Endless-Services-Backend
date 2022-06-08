<?php

namespace App\Http\Presenters\GDCN\Dashboard;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserPresenter
{
    public function renderProfile(): Response
    {
        return Inertia::render('GDCN/User/Profile', [
            'gdcn' => [
                'user' => Auth::user()
                    ?->only(['id', 'name', 'email', 'email_verified_at', 'created_at'])
            ]
        ]);
    }

    public function renderSetting(): Response
    {
        return Inertia::render('GDCN/User/Setting', [
            'gdcn' => [
                'user' => Auth::user()
                    ?->only(['id', 'name', 'email'])
            ]
        ]);
    }
}
