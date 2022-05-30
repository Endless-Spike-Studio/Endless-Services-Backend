<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelRating;
use App\Models\GDCS\User;
use App\Models\GDCS\UserScore;
use Inertia\Inertia;
use Inertia\Response;

class DashboardPresenter
{
    public function renderHome(): Response
    {
        $now = now();
        $perPage = config('gdcs.perPage', 10);
        $startOfDay = $now->startOfDay();

        return Inertia::render('GDCS/Dashboard/Home', [
            'todayRegisteredAccountCount' => Account::where('created_at', '>=', $startOfDay)->count(),
            'todayUploadedLevelCount' => Level::where('created_at', '>=', $startOfDay)->count(),
            'todayRatedLevelCount' => LevelRating::where('created_at', '>=', $startOfDay)->count(),
            'recentRegisteredAccounts' => Account::orderByDesc('created_at')
                ->take($perPage)
                ->get(['id', 'name', 'created_at']),
            'leaderboards' => UserScore::with('user:id,name')
                ->whereHas('user')
                ->orderByDesc('stars')
                ->take($perPage)
                ->get(['user_id', 'stars']),
            'recentUploadedLevels' => Level::with('user:id,name')
                ->whereHas('user')
                ->orderByDesc('created_at')
                ->take($perPage)
                ->get(['id', 'name', 'user_id', 'created_at']),
            'recentRatedLevels' => Level::with('user:id,name')
                ->whereHas('user')
                ->whereHas('rating', static function ($query) {
                    $query->where('stars', '>', 0);
                })
                ->take($perPage)
                ->get(['id', 'name', 'user_id', 'created_at']),
            'recentFeaturedLevels' => Level::with('user:id,name')
                ->whereHas('user')
                ->whereHas('rating', static function ($query) {
                    $query->where('featured_score', '>', 0);
                })
                ->take($perPage)
                ->get(['id', 'name', 'user_id', 'created_at']),
            'recentEpicLevels' => Level::with('user:id,name')
                ->whereHas('user')
                ->whereHas('rating', static function ($query) {
                    $query->where('epic', true);
                })
                ->take($perPage)
                ->get(['id', 'name', 'user_id', 'created_at'])
        ]);
    }

    public function renderAccountInfo(int $id): Response
    {
        return Inertia::render('GDCS/Dashboard/Account/Info', [
            'account' => Account::findOrFail($id, ['id', 'name', 'created_at'])
                ->load('comments:id,account_id,comment,likes,created_at')
                ->load('user:id,name,uuid')
        ]);
    }

    public function renderLevelInfo(int $id): Response
    {
        return Inertia::render('GDCS/Dashboard/Level/Info', [
            'level' => Level::findOrFail($id, ['id', 'user_id', 'name', 'desc', 'downloads', 'likes', 'version', 'length', 'audio_track', 'song_id', 'original_level_id', 'two_player', 'objects', 'coins', 'requested_stars', 'unlisted', 'ldm', 'created_at', 'updated_at'])
                ->load('user:id,name')
                ->load('song:id,song_id,name')
                ->load('original:id,name')
                ->load('rating:id,level_id,difficulty,featured_score,epic,demon_difficulty,auto,demon,stars,coin_verified,created_at')
                ->load('comments:id,account_id,level_id,comment,likes,created_at')
                ->load('comments.account:id,name')
        ]);
    }

    public function renderUserInfo(int $id): Response
    {
        return Inertia::render('GDCS/Dashboard/User/Info', [
            'user' => User::findOrFail($id, ['id', 'name', 'uuid', 'created_at'])
                ->load('account:id,name')
                ->load('score:id,user_id,stars,demons,creator_points,coins,user_coins,updated_at')
        ]);
    }
}
