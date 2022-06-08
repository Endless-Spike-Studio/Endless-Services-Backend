<?php

namespace App\Http\Presenters\GDCS\Tools;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountLinkPresenter
{
    public function list(): Response
    {
        return Inertia::render('GDCS/Tools/Account/Link/List', [
            'links' => Request::user('gdcs')
                ?->getRelationValue('links')
        ]);
    }
}
