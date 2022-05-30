<?php

namespace App\Http\Presenters\GDCS;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class LevelTempUploadAccessToolPresenter
{
    public function list(): Response
    {
        return Inertia::render('GDCS/Tools/Level/TempUploadAccess/List', [
            'accesses' => Request::user('gdcs')
                ->getRelationValue('tempLevelUploadAccesses')
        ]);
    }
}
