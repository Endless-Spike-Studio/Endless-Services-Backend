<?php

namespace App\Services\Game;

use App\Models\GDCS\BannedUser;
use App\Models\GDCS\DailyLevel;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelGauntlet;
use App\Models\GDCS\LevelPack;
use App\Models\GDCS\LevelRating;
use App\Models\GDCS\User;
use App\Models\GDCS\WeeklyLevel;

class AntiCheatService
{
    public static function run(): void
    {
        $maxStars = static::calculateMaxStars();

        User::query()
            ->whereHas('score', function ($query) use ($maxStars) {
                $query->where('stars', '>', $maxStars);
            })
            ->with(['score:user_id,stars'])
            ->get()
            ->each(function (User $user) {
                BannedUser::updateOrCreate([
                    'user_id' => $user->id
                ], [
                    'login_ban' => true,
                    'reason' => "AutoBan: Stars |  {$user->score->stars}",
                    'expires_at' => null
                ]);
            });

        $maxGoldCoins = static::calculateMaxGoldCoins();

        User::query()
            ->whereHas('score', function ($query) use ($maxGoldCoins) {
                $query->where('coins', '>', $maxGoldCoins);
            })
            ->with(['score:user_id,coins'])
            ->get()
            ->each(function (User $user) {
                BannedUser::updateOrCreate([
                    'user_id' => $user->id
                ], [
                    'login_ban' => true,
                    'reason' => "AutoBan: Gold Coins |  {$user->score->coins}",
                    'expires_at' => null
                ]);
            });

        $maxSilverCoins = static::calculateMaxSilverCoins();

        User::query()
            ->whereHas('score', function ($query) use ($maxSilverCoins) {
                $query->where('user_coins', '>', $maxSilverCoins);
            })
            ->with(['score:user_id,user_coins'])
            ->get()
            ->each(function (User $user) {
                BannedUser::updateOrCreate([
                    'user_id' => $user->id
                ], [
                    'login_ban' => true,
                    'reason' => "AutoBan: Silver Coins |  {$user->score->user_coins}",
                    'expires_at' => null
                ]);
            });

        $maxDemons = static::calculateMaxDemons();

        User::query()
            ->whereHas('score', function ($query) use ($maxDemons) {
                $query->where('demons', '>', $maxDemons);
            })
            ->with(['score:user_id,demons'])
            ->get()
            ->each(function (User $user) {
                BannedUser::updateOrCreate([
                    'user_id' => $user->id
                ], [
                    'login_ban' => true,
                    'reason' => "AutoBan: Demons |  {$user->score->demons}",
                    'expires_at' => null
                ]);
            });
    }

    public static function calculateMaxStars(): int
    {
        return array_sum([
            190,
            LevelRating::query()
                ->sum('stars'),
            LevelPack::query()
                ->sum('stars'),
            LevelGauntlet::query()
                ->select(['level1_id', 'level2_id', 'level3_id', 'level4_id', 'level5_id'])
                ->get()
                ->map(function (LevelGauntlet $gauntlet) {
                    return LevelRating::query()
                        ->whereIn('level_id', $gauntlet->levels)
                        ->sum('stars');
                })->sum(),
            DailyLevel::query()
                ->select(['level_id'])
                ->get()
                ->map(function (DailyLevel $daily) {
                    return LevelRating::query()
                        ->where('level_id', $daily->level_id)
                        ->sum('stars');
                })->sum(),
            WeeklyLevel::query()
                ->select(['level_id'])
                ->get()
                ->map(function (WeeklyLevel $weekly) {
                    return LevelRating::query()
                        ->where('level_id', $weekly->level_id)
                        ->sum('stars');
                })->sum()
        ]);
    }

    public static function calculateMaxGoldCoins(): int
    {
        return array_sum([
            66,
            LevelPack::query()
                ->sum('coins')
        ]);
    }

    public static function calculateMaxSilverCoins(): int
    {
        return array_sum([
            Level::query()
                ->whereHas('rating', function ($query) {
                    $query->where('coin_verified', true);
                })->sum('coins')
        ]);
    }

    public static function calculateMaxDemons(): int
    {
        return array_sum([
            3,
            Level::query()
                ->whereHas('rating', function ($query) {
                    $query->where('demon', true);
                })->count(),
            LevelGauntlet::query()
                ->select(['level1_id', 'level2_id', 'level3_id', 'level4_id', 'level5_id'])
                ->get()
                ->map(function (LevelGauntlet $gauntlet) {
                    return LevelRating::query()
                        ->where('demon', true)
                        ->whereIn('level_id', $gauntlet->levels)
                        ->count();
                })->sum(),
            DailyLevel::query()
                ->select(['level_id'])
                ->get()
                ->map(function (DailyLevel $daily) {
                    return LevelRating::query()
                        ->where('level_id', $daily->level_id)
                        ->where('demon', true)
                        ->count();
                })->sum(),
            WeeklyLevel::query()
                ->select(['level_id'])
                ->get()
                ->map(function (WeeklyLevel $weekly) {
                    return LevelRating::query()
                        ->where('level_id', $weekly->level_id)
                        ->where('demon', true)
                        ->count();
                })->sum()
        ]);
    }
}
