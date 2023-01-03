<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\LevelComment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountPresenter
{
    public function renderProfile(): Response
    {
        $account = Auth::guard('gdcs')->user();
        return $this->renderInfo($account);
    }

    public function renderInfo(Account $account): Response
    {
        $isOwner = Auth::guard('gdcs')->id() === $account->id;
        $columns = ['id', 'name', 'created_at', 'user'];
        $userColumns = ['id', 'uuid'];

        if ($isOwner) {
            $columns[] = 'email';
            $columns[] = 'email_verified_at';
            $userColumns[] = 'name';
            $userColumns[] = 'udid';
        }

        $account->load(['user:' . implode(',', $userColumns), 'user.score:user_id,stars,demons,creator_points']);

        return Inertia::render('GDCS/Account/Info', [
            'account' => $account->only($columns),
            'is_owner' => $isOwner,
            'statistic' => [
                'friends' => $account->friends()
                    ->count(),
                'levels' => $account->user->levels()
                    ->count(),
                'comments' => array_sum([
                    $account->comments()
                        ->count(),
                    LevelComment::query()
                        ->where('account_id', $account->id)
                        ->count()
                ])
            ],
            'comments' => Inertia::lazy(function () use ($account) {
                return $account->comments()
                    ->select(['account_id', 'comment', 'likes', 'created_at'])
                    ->paginate();
            }),
            'levels' => Inertia::lazy(function () use ($account) {
                return $account->user
                    ->levels()
                    ->with(['rating:level_id,difficulty,stars,featured_score,epic,auto,demon,demon_difficulty,created_at', 'creator:id,uuid', 'creator.account:id,name'])
                    ->select(['id', 'name', 'desc', 'user_id', 'created_at'])
                    ->whereNot('unlisted', true)
                    ->latest()
                    ->paginate();
            })
        ]);
    }
}
