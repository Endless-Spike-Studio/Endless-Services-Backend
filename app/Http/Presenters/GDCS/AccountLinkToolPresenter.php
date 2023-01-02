<?php

namespace App\Http\Presenters\GDCS;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountLinkToolPresenter
{
    public function renderIndex(): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Account/Link/Home', [
            'links' => $account->links()
                ->select(['id', 'account_id', 'server', 'target_name', 'target_account_id', 'target_user_id', 'created_at'])
                ->paginate()
        ]);
    }
}
