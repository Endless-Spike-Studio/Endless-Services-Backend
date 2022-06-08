<?php

namespace App\Http\Presenters\GDCS\Dashboard;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountPresenter
{
    public function renderProfile(): Response
    {
        return Inertia::render('GDCS/Account/Profile', [
            'gdcs' => [
                'account' => Auth::guard('gdcs')
                    ->user()
                    ->only(['id', 'name', 'email', 'email_verified_at', 'created_at']),
                'user' => Auth::guard('gdcs')
                    ->user()
                    ->load('user:id,name,uuid,udid,created_at')
                    ->getRelation('user')
            ]
        ]);
    }

    public function renderSetting(): Response
    {
        return Inertia::render('GDCS/Account/Setting', [
            'gdcs' => [
                'account' => Auth::guard('gdcs')
                    ->user()
                    ->only(['id', 'name', 'email'])
            ]
        ]);
    }

    public function renderFailedLogs(): Response
    {
        return Inertia::render('GDCS/Account/FailedLog', [
            'logs' => Auth::guard('gdcs')
                ->user()
                ->load('failedLogs:id,account_id,content,ip,created_at')
                ->getRelation('failedLogs')
        ]);
    }
}
