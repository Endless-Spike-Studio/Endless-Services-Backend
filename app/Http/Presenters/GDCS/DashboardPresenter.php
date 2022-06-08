<?php

namespace App\Http\Presenters\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelRating;
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
}
