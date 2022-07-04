<?php

namespace App\Http\Presenters\GDCS\Tools;

use App\Models\GDCS\Account;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountLinkPresenter
{
    public function renderList(): Response
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Account/Link/List', [
            'links' => $account->load('links:id,account_id,server,target_name,target_account_id,target_user_id,created_at')
                ->getRelation('links')
        ]);
    }
}
