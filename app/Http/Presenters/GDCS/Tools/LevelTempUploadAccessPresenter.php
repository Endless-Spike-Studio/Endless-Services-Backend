<?php

namespace App\Http\Presenters\GDCS\Tools;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LevelTempUploadAccessPresenter
{
    public function list(): Response
    {
        return Inertia::render('GDCS/Tools/Level/TempUploadAccess/List', [
            'accesses' => Auth::guard('gdcs')
                ->user()
                ->load('tempLevelUploadAccesses:id,ip,created_at')
                ->getRelation('tempLevelUploadAccesses')
        ]);
    }
}
