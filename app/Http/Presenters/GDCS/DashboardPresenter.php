<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\CustomSong;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use App\Services\Game\CustomSongService;
use App\Services\NGProxy\SongService;
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
                    ->with(['rating:level_id,difficulty,stars,featured_score,epic,auto,demon,demon_difficulty,created_at', 'creator:id,uuid', 'creator.account:id,name'])
                    ->select(['id', 'name', 'desc', 'user_id', 'created_at'])
                    ->whereNot('unlisted', true)
                    ->latest()
                    ->paginate(pageName: 'page_levels'),
                'ratedLevels' => Level::query()
                    ->with(['rating:level_id,difficulty,stars,featured_score,epic,auto,demon,demon_difficulty,created_at', 'creator:id,uuid', 'creator.account:id,name'])
                    ->whereHas('rating', function ($query) {
                        $query->where('stars', '>', 0);
                    })
                    ->select(['id', 'name', 'desc', 'user_id', 'created_at'])
                    ->whereNot('unlisted', true)
                    ->latest()
                    ->paginate(pageName: 'page_ratedLevels')
            ]
        ]);
    }

    public function renderLevelInfo(Level $level): Response
    {
        return Inertia::render('GDCS/Dashboard/Level/Info', [
            'customSongOffset' => CustomSongService::$offset,
            'level' => $level->load(['rating:level_id,difficulty,stars,featured_score,epic,coin_verified,auto,demon,demon_difficulty', 'creator:id,uuid', 'creator.account:id,name'])
                ->only(['id', 'name', 'desc', 'length', 'objects', 'requested_stars', 'unlisted', 'coins', 'audio_track', 'song_id', 'created_at', 'rating', 'creator']),
            'song' => Inertia::lazy(function () use ($level) {
                if ($level->song_id > CustomSongService::$offset) {
                    return CustomSong::query()
                        ->where('id', $level->song_id - CustomSongService::$offset)
                        ->select(['id', 'name', 'account_id', 'artist_name', 'size', 'download_url'])
                        ->with(['account:id,name'])
                        ->first();
                } else if ($level->song_id > 0) {
                    return app(SongService::class)->find($level->song_id, true);
                }

                return null;
            }),
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
