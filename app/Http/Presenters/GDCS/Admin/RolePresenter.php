<?php

namespace App\Http\Presenters\GDCS\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Silber\Bouncer\Database\Role;

class RolePresenter
{
    public function renderList(): Response
    {
        return Inertia::render('GDCS/Admin/Permission/Role/List', [
            'roles' => Role::all()
        ]);
    }

    public function renderInfo(Role $role): Response
    {
        return Inertia::render(
            'GDCS/Admin/Permission/Role/Info',
            compact('role')
        );
    }
}
