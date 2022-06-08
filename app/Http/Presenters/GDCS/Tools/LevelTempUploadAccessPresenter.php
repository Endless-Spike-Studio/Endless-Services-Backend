<?php

namespace App\Http\Presenters\GDCS\Tools;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class LevelTempUploadAccessPresenter
{
    public function list(): Response
    {
        return Inertia::render('GDCS/Tools/Level/TempUploadAccess/List', [
            'accesses' => Request::user('gdcs')
                ->getRelationValue('tempLevelUploadAccesses')
        ]);
    }
}
