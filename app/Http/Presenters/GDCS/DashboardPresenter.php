<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardPresenter
{
    public function renderHome(): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Dashboard/Home', [
            'account' => $account->only(['name', 'created_at']),
            'statistic' => [
                'comments' => array_sum([
                    $account->comments()
                        ->count(),
                    LevelComment::query()
                        ->where('account_id', $account->id)
                        ->count()
                ]),
                'levels' => $account->user->levels()
                    ->count(),
                'likes' => array_sum([
                    $account->comments()
                        ->sum('likes'),
                    LevelComment::query()
                        ->where('account_id', $account->id)
                        ->sum('likes'),
                    $account->user->levels()
                        ->sum('likes')
                ])
            ],
            'latest' => [
                'accounts' => Account::query()
                    ->select(['id', 'name', 'created_at'])
                    ->latest()
                    ->paginate(pageName: 'page_accounts'),
                'levels' => Level::query()
                    ->select(['id', 'name', 'desc', 'created_at'])
                    ->whereNot('unlisted', true)
                    ->latest()
                    ->paginate(pageName: 'page_levels'),
                'ratedLevels' => Level::query()
                    ->with('rating:level_id,stars,featured_score,epic,demon_difficulty')
                    ->whereHas('rating', function ($query) {
                        $query->where('stars', '>', 0);
                    })
                    ->select(['id', 'name', 'desc', 'created_at'])
                    ->whereNot('unlisted', true)
                    ->latest()
                    ->paginate(pageName: 'page_ratedLevels')
            ]
        ]);
    }

    public function renderLevelInfo(Level $level): Response
    {
        return Inertia::render('GDCS/Dashboard/Level/Info', [
            'customSongOffset' => config('gdcn.game.custom_song_offset'),
            'level' => $level->load(['rating:level_id,stars,featured_score,epic,coin_verified,demon_difficulty', 'creator:id,uuid', 'creator.account:id,name', 'song:song_id,name,artist_name,size'])
                ->only(['id', 'name', 'desc', 'length', 'objects', 'requested_stars', 'unlisted', 'coins', 'audio_track', 'song_id', 'song', 'created_at', 'rating', 'creator']),
            'comments' => Inertia::lazy(function () use ($level) {
                return $level->comments()
                    ->select(['account_id', 'level_id', 'comment', 'percent', 'likes', 'created_at'])
                    ->with('account:id,name')
                    ->paginate();
            }),
            'scores' => Inertia::lazy(function () use ($level) {
                return $level->scores()
                    ->select(['account_id', 'level_id', 'attempts', 'percent', 'coins', 'created_at'])
                    ->orderByDesc('percent')
                    ->with('account:id,name')
                    ->paginate();
            })
        ]);
    }
}
