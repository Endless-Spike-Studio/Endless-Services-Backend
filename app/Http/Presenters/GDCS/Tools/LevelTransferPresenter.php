<?php

namespace App\Http\Presenters\GDCS\Tools;

use App\Models\GDCS\Account;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LevelTransferPresenter
{
    public function renderHome(): Response
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Level/Transfer/Home', [
            'links' => $account->links
        ]);
    }
}
