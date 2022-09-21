<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\LevelComment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardPresenter
{
    public function renderAccountInfo(Account $account): Response
    {
        $extraUserLoad = null;
        $isOwner = Auth::guard('gdcs')
            ->user()
            ->is($account);

        if ($isOwner) {
            $extraUserLoad = ',udid';
        }

        return Inertia::render('GDCS/Dashboard/Account/Info', [
            'account' => $account->load([
                'user:id,uuid' . $extraUserLoad,
                'user.score:user_id,stars,demons,creator_points',
                'user.levels:id,name,user_id,created_at',
                'comments:account_id,comment,created_at'
            ])->only(['id', 'name', 'created_at', 'user', 'comments']),
            'friends' => $account->friends()
                ->select(['account_id', 'friend_account_id', 'created_at'])
                ->with(['account:id,name', 'friend_account:id,name'])
                ->get(),
            'comments_count' => array_sum([
                $account->comments->count(),
                LevelComment::query()
                    ->where('account_id', $account->id)
                    ->count()
            ]),
            'levels_count' => $account->user->levels->count()
        ]);
    }
}
