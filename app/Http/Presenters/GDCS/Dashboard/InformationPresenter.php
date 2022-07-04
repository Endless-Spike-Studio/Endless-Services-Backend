<?php

namespace App\Http\Presenters\GDCS\Dashboard;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use App\Models\GDCS\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class InformationPresenter
{

    public function renderUser(User $user): Response
    {
        return Inertia::render('GDCS/Dashboard/User/Info', [
            'user' => $user->select(['id', 'name', 'uuid', 'created_at'])
                ->load('account:id,name')
                ->load('score:id,user_id,stars,demons,creator_points,coins,user_coins,updated_at')
        ]);
    }

    public function renderLevel(Level $level): Response
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        if ($level->unlisted) {
            abort(404);
        }

        return Inertia::render('GDCS/Dashboard/Level/Info', [
            'level' => $level->select(['id', 'user_id', 'name', 'desc', 'downloads', 'likes', 'version', 'length', 'password', 'audio_track', 'song_id', 'original_level_id', 'two_player', 'objects', 'coins', 'requested_stars', 'unlisted', 'ldm', 'created_at', 'updated_at'])
                ->load('user:id,name')
                ->load('song:id,song_id,name')
                ->load('original:id,name')
                ->load('rating:id,level_id,difficulty,featured_score,epic,demon_difficulty,auto,demon,stars,coin_verified,created_at')
                ->load('comments:id,account_id,level_id,comment,likes,created_at')
                ->load('comments.account:id,name')
                ->load('daily:id,level_id,apply_at')
                ->load('weekly:id,level_id,apply_at'),
            'permission' => [
                'rate' => $account->can('RATE_LEVEL'),
                'mark' => $account->can('MARK_LEVEL')
            ]
        ]);
    }

    public function renderAccount(Account $account): Response
    {
        return Inertia::render('GDCS/Dashboard/Account/Info', [
            'account' => $account->select(['id', 'name', 'created_at'])
                ->load('comments:id,account_id,comment,likes,created_at')
                ->load('user:id,name,uuid')
        ]);
    }
}
