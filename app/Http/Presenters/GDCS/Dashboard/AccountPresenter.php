<?php

namespace App\Http\Presenters\GDCS\Dashboard;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountPresenter
{
    public function renderProfile(Request $request): Response
    {
        return Inertia::render('GDCS/Account/Profile', [
            'gdcs' => [
                'account' => $request->user('gdcs')
                    ?->select(['id', 'name', 'email', 'email_verified_at', 'created_at'])
                    ?->firstOrFail(),
                'user' => $request->user('gdcs')
                    ?->select(['id'])
                    ?->with('user:id,name,uuid,udid,created_at')
                    ?->firstOrFail()
                    ?->getRelation('user')
            ]
        ]);
    }

    public function renderSetting(Request $request): Response
    {
        return Inertia::render('GDCS/Account/Setting', [
            'gdcs' => [
                'account' => $request->user('gdcs')
                    ?->select(['id', 'name', 'email'])
                    ?->firstOrFail()
            ]
        ]);
    }

    public function renderFailedLogs(Request $request): Response
    {
        return Inertia::render('GDCS/Account/FailedLog', [
            'logs' => $request->user('gdcs')
                ?->select(['id'])
                ?->with('failedLogs:id,account_id,content,ip,created_at')
                ?->firstOrFail()
                ?->getRelation('failedLogs')
        ]);
    }
}
