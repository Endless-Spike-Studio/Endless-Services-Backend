<?php

namespace App\Http\Presenters\GDCS;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LevelTempUploadAccessToolPresenter
{
    public function renderIndex(): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Level/TempUploadAccess/Home', [
            'accesses' => $account->tempLevelUploadAccesses()
                ->select(['id', 'account_id', 'ip', 'created_at'])
                ->get()
        ]);
    }
}
