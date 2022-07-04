<?php

namespace App\Http\Presenters\GDCS\Dashboard;

use App\Models\GDCS\Account;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountPresenter
{
    public function renderProfile(): Response
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Account/Profile', [
            'gdcs' => [
                'account' => $account->only(['id', 'name', 'email', 'email_verified_at', 'created_at']),
                'user' => $account->load('user:id,name,uuid,udid,created_at')
                    ->getRelationValue('user')
            ]
        ]);
    }

    public function renderSetting(): Response
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Account/Setting', [
            'gdcs' => [
                'account' => $account->only(['id', 'name', 'email'])
            ]
        ]);
    }

    public function renderFailedLogs(): Response
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Account/FailedLog', [
            'logs' => $account->load('failedLogs:id,account_id,content,ip,created_at')
                ->getRelationValue('failedLogs')
        ]);
    }
}
