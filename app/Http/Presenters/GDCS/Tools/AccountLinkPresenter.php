<?php

namespace App\Http\Presenters\GDCS\Tools;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountLinkPresenter
{
    public function list(): Response
    {
        return Inertia::render('GDCS/Tools/Account/Link/List', [
            'links' => Auth::guard('gdcs')
                ->user()
                ->load('links:id,account_id,server,target_name,target_account_id,target_user_id,created_at')
                ->getRelation('links')
        ]);
    }
}
