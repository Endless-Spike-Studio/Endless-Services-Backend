<?php

namespace App\Http\Presenters\GDCS\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Silber\Bouncer\Database\Ability;

class AbilityPresenter
{
    public function renderList(): Response
    {
        return Inertia::render('GDCS/Admin/Permission/Ability/List', [
            'abilities' => Ability::all(),
        ]);
    }

    public function renderInfo(Ability $ability): Response
    {
        return Inertia::render(
            'GDCS/Admin/Permission/Ability/Info',
            compact('ability')
        );
    }
}
