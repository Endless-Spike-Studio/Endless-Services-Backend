<?php

namespace App\Http\Presenters\GDCS\Admin;

use App\Models\GDCS\Level;
use Inertia\Inertia;
use Inertia\Response;

class LevelPresenter
{
    public function renderRate(Level $level): Response
    {
        return Inertia::render('GDCS/Admin/Level/Rate', [
            'level' => $level
        ]);
    }
}
