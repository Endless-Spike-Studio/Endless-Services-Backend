<?php

namespace App\Http\Presenters\GDCS;

use App\Exceptions\GDCS\WebException;
use App\Models\GDCS\Account;
use App\Models\GDCS\Contest;
use App\Models\GDCS\CustomSong;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use App\Models\GDCS\LevelRating;
use App\Models\GDCS\UserScore;
use App\Services\Game\CustomSongService;
use App\Services\NGProxy\SongService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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
                'contests' => Contest::query()
                    ->select('id', 'name', 'desc', 'deadline_at', 'created_at')
                    ->latest()
                    ->paginate(),
                'levels' => Level::query()
                    ->with(['rating:level_id,difficulty,stars,featured_score,epic,auto,demon,demon_difficulty,created_at', 'creator:id,uuid', 'creator.account:id,name'])
                    ->select(['id', 'name', 'desc', 'user_id', 'created_at'])
                    ->whereNot('unlisted', true)
                    ->latest()
                    ->paginate(pageName: 'page_levels'),
                'ratedLevels' => LevelRating::query()
                    ->where('stars', '>', 0)
                    ->select(['level_id', 'difficulty', 'stars', 'featured_score', 'epic', 'auto', 'demon', 'demon_difficulty', 'created_at'])
                    ->with(['level:id,name,desc,user_id,created_at', 'level.creator:id,uuid', 'level.creator.account:id,name'])
                    ->whereDoesntHave('level', function ($query) {
                        $query->where('unlisted', true);
                    })
                    ->whereHas('level')
                    ->latest()
                    ->paginate(pageName: 'page_ratedLevels'),
                'scores' => function () {
                    $query = UserScore::query()
                        ->whereDoesntHave('user.ban')
                        ->select(['user_id', 'stars', 'diamonds', 'coins', 'user_coins', 'demons', 'creator_points', 'updated_at'])
                        ->with('user:id,name');

                    $sort = [
                        'column' => Request::get('sort_scores_column', 'stars'),
                        'order' => Request::get('sort_scores_order', 'desc')
                    ];

                    if (
                        !in_array($sort['column'], ['stars', 'coins', 'user_coins', 'demons', 'creator_points'], true) ||
                        !in_array($sort['order'], ['asc', 'desc'], true)
                    ) {
                        throw new WebException(__('gdcn.dashboard.error.invalid_arguments'));
                    }

                    $query->orderBy($sort['column'], $sort['order']);
                    return $query->paginate(pageName: 'page_scores');
                }
            ]
        ]);
    }

    public function renderLevelInfo(Level $level): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Dashboard/Level/Info', [
            'customSongOffset' => CustomSongService::$offset,
            'level' => $level->load(['rating:level_id,difficulty,stars,featured_score,epic,coin_verified,auto,demon,demon_difficulty', 'creator:id,uuid', 'creator.account:id,name'])
                ->only(['id', 'name', 'desc', 'length', 'downloads', 'likes', 'objects', 'requested_stars', 'version', 'unlisted', 'coins', 'audio_track', 'song_id', 'created_at', 'rating', 'creator']),
            'song' => Inertia::lazy(function () use ($level) {
                if ($level->song_id > CustomSongService::$offset) {
                    return CustomSong::query()
                        ->whereKey($level->song_id - CustomSongService::$offset)
                        ->select(['id', 'name', 'account_id', 'artist_name', 'size', 'download_url'])
                        ->with(['account:id,name'])
                        ->first();
                } else if ($level->song_id > 0) {
                    return app(SongService::class)
                        ->find($level->song_id, true)
                        ->only(['name', 'artist_name', 'size']);
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
            }),
            'can' => [
                'edit' => $account->user->can('edit', $level)
            ],
            'settings' => Inertia::lazy(function () use ($level) {
                return $level->only(['name', 'desc', 'password', 'audio_track', 'song_id', 'unlisted']);
            })
        ]);
    }

    public function renderContestInfo(Contest $contest): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Dashboard/Contest/Info', [
            'contest' => $contest->load(['account:id,name'])
                ->only(['id', 'name', 'desc', 'rules', 'account', 'deadline_at', 'created_at']),
            'participants' => Inertia::lazy(function () use ($contest) {
                return $contest->participants()
                    ->select(['contest_id', 'account_id', 'level_id', 'created_at'])
                    ->with(['account:id,name', 'level:id,name,desc,length,downloads,likes,objects,requested_stars,version,unlisted,coins,audio_track,song_id,user_id,created_at', 'level.rating:level_id,difficulty,stars,featured_score,epic,coin_verified,auto,demon,demon_difficulty', 'level.creator:id,uuid', 'level.creator.account:id,name'])
                    ->paginate();
            }),
            'levels' => Inertia::lazy(function () use ($account) {
                return $account->user
                    ->levels()
                    ->select(['id', 'name', 'desc', 'length', 'downloads', 'likes', 'objects', 'requested_stars', 'version', 'unlisted', 'coins', 'audio_track', 'song_id', 'created_at'])
                    ->with(['rating:level_id,difficulty,stars,featured_score,epic,coin_verified,auto,demon,demon_difficulty'])
                    ->paginate();
            }),
            'can' => [
                'submit' => $account->can('submit', $contest)
            ]
        ]);
    }
}
