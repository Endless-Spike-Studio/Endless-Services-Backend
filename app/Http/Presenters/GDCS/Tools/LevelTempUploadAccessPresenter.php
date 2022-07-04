<?php

namespace App\Http\Presenters\GDCS\Tools;

use App\Models\GDCS\Account;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LevelTempUploadAccessPresenter
{
    public function list(): Response
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Level/TempUploadAccess/List', [
            'accesses' => $account->load('tempLevelUploadAccesses:id,account_id,ip,created_at')
                ->getRelationValue('tempLevelUploadAccesses')
        ]);
    }
}
