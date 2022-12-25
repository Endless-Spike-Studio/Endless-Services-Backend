<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\AccountComment;
use App\Models\GDCS\Contest;
use App\Models\GDCS\CustomSong;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use App\Models\GDCS\LevelPack;
use App\Models\GDCS\LevelRating;
use App\Models\GDCS\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomePresenter
{
    public function render()
    {
        return Inertia::render('GDCS/Home', [
            'statistic' => [
                'players' => User::query()
                    ->count(),
                'accounts' => Account::query()
                    ->count(),
                'moderators' => DB::table('assigned_roles')
                    ->count(),
                'levels' => Level::query()
                    ->count(),
                'levelPacks' => LevelPack::query()
                    ->count(),
                'ratedLevels' => LevelRating::query()
                    ->where('stars', '>', 0)
                    ->count(),
                'comments' => array_sum([
                    AccountComment::query()
                        ->count(),
                    LevelComment::query()
                        ->count()
                ]),
                'customSongs' => CustomSong::query()
                    ->count(),
                'contests' => Contest::query()
                    ->count()
            ]
        ]);
    }
}
