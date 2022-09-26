<?php

namespace App\Http\Presenters\GDCS\Tools;

use App\Models\GDCS\Account;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountLinkPresenter
{
    public function renderHome(): Response
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Account/Link/Home', [
            'links' => $account->links
        ]);
    }
}
