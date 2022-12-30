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
            'links' => $account->links
        ]);
    }
}
