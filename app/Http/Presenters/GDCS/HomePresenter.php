<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\AccountComment;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use App\Models\GDCS\User;
use Inertia\Inertia;
use Inertia\Response;

class HomePresenter
{
    public function renderHome(): Response
    {
        return Inertia::render('GDCS/Home', [
            'count' => [
                'players' => User::query()
                    ->count(),
                'accounts' => Account::query()
                    ->count(),
                'levels' => Level::query()
                    ->count(),
                'comments' => array_sum([
                    AccountComment::query()
                        ->count(),
                    LevelComment::query()
                        ->count()
                ])
            ]
        ]);
    }
}
