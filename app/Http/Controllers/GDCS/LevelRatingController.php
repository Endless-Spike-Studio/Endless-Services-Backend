<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\LevelRatingDemonDifficulty;
use App\Enums\GDCS\LevelRatingDifficulty;
use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LevelRateDemonRequest;
use App\Http\Requests\GDCS\LevelRateStarRequest;
use App\Http\Requests\GDCS\LevelSuggestStarRequest;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelDemonDifficultySuggestion;
use App\Models\GDCS\LevelRating;
use App\Models\GDCS\LevelRatingSuggestion;
use App\Models\GDCS\LevelStarSuggestion;

class LevelRatingController extends Controller
{
    public function rateStars(LevelRateStarRequest $request): int
    {
        $data = $request->validated();

        $level = Level::query()
            ->findOrFail($data['levelID']);

        if (! empty($level->rating)) {
            return Response::LEVEL_RATE_FAILED_ALREADY_RATED->value;
        }

        $ip = $request->ip();
        $userID = $request->user?->id;

        $record = LevelStarSuggestion::query()
            ->where('level_id', $data['levelID'])
            ->where('ip', $ip)
            ->where('user_id', $userID);

        if (! $record->exists()) {
            foreach ($request->account->groups as $group) {
                if ($group->can('direct-rate')) {
                    LevelStarSuggestion::query()
                        ->where('level_id', $data['levelID'])
                        ->delete();

                    if (! $this->rateNoStars($level, $data['stars'])) {
                        return \App\Enums\Response::LEVEL_RATE_FAILED_UNKNOWN_ERROR->value;
                    }

                    return \App\Enums\Response::LEVEL_RATE_SUCCESS->value;
                }
            }

            $record = new LevelStarSuggestion();
            $record->level_id = $data['levelID'];
            $record->stars = $data['stars'];
            $record->ip = $ip;
            $record->user_id = $userID;
            $record->save();

            $query = LevelStarSuggestion::query()
                ->where('level_id', $data['levelID']);

            if ($query->count() >= config('gdcs.suggestion.minimum_count.rate', 10)) {
                $avgStars = round($query->average('stars'));
                if ($avgStars < 1 || $avgStars > 10) {
                    return \App\Enums\Response::LEVEL_RATE_FAILED_UNKNOWN_ERROR->value;
                }

                $query->delete();
                if (! $this->rateNoStars($level, $data['stars'])) {
                    return \App\Enums\Response::LEVEL_RATE_FAILED_UNKNOWN_ERROR->value;
                }

                return Response::LEVEL_RATE_SUCCESS->value;
            }

            return \App\Enums\Response::LEVEL_RATE_SUGGESTION_SUBMITTED->value;
        }

        return \App\Enums\Response::LEVEL_RATE_FAILED_USER_ALREADY_RATED->value;
    }

    protected function rateNoStars(Level $level, int $stars): bool
    {
        if (! empty($level->rating)) {
            return false;
        }

        $rating = new LevelRating();
        $rating->level_id = $level->id;
        $rating->stars = 0;
        $rating->difficulty = HelperController::guessLevelRatingDifficultyFromStars($stars);
        $rating->auto = $rating->difficulty === LevelRatingDifficulty::AUTO;
        $rating->demon = $rating->difficulty === LevelRatingDifficulty::DEMON;

        return $rating->save();
    }

    public function rateDemon(LevelRateDemonRequest $request): int
    {
        $data = $request->validated();

        $level = Level::query()
            ->findOrFail($data['levelID']);

        if (empty($level->rating)) {
            return Response::LEVEL_RATE_DEMON_FAILED_NOT_RATED->value;
        }

        if (! empty($data['mode'])) {
            if ($request->account->mod_level->value <= 0) {
                return \App\Enums\Response::LEVEL_RATE_DEMON_FAILED_NO_PERMISSION->value;
            }

            foreach ($request->account->groups as $group) {
                if ($group->can('direct-rate')) {
                    LevelDemonDifficultySuggestion::query()
                        ->where('level_id', $data['levelID'])
                        ->delete();

                    if (! $this->rateDemonDiff($level, $data['rating'])) {
                        return \App\Enums\Response::LEVEL_RATE_DEMON_FAILED_UNKNOWN_ERROR->value;
                    }

                    return \App\Enums\Response::LEVEL_RATE_DEMON_SUCCESS->value;
                }
            }
        }

        $ip = $request->ip();
        $userID = $request->user?->id;

        $record = LevelDemonDifficultySuggestion::query()
            ->where('level_id', $data['levelID'])
            ->where('ip', $ip)
            ->where('user_id', $userID);

        if (! $record->exists()) {
            $record = new LevelDemonDifficultySuggestion();
            $record->level_id = $data['levelID'];
            $record->demon_diff = $data['rating'];
            $record->ip = $ip;
            $record->user_id = $userID;
            $record->save();

            $query = LevelDemonDifficultySuggestion::query()
                ->where('level_id', $data['levelID']);

            if ($query->count() >= config('gdcs.suggestion.minimum_count.demon', 10)) {
                $avgRating = round($query->average('demon_diff'));
                if ($avgRating < 1 || $avgRating > 5) {
                    return \App\Enums\Response::LEVEL_RATE_DEMON_FAILED_UNKNOWN_ERROR->value;
                }

                if (! $this->rateDemonDiff($level, $avgRating)) {
                    return Response::LEVEL_RATE_DEMON_FAILED_UNKNOWN_ERROR->value;
                }

                $query->delete();

                return \App\Enums\Response::LEVEL_RATE_DEMON_SUCCESS->value;
            }

            return \App\Enums\Response::LEVEL_RATE_DEMON_SUGGESTION_SUBMITTED->value;
        }

        return \App\Enums\Response::LEVEL_RATE_DEMON_FAILED_USER_ALREADY_RATED->value;
    }

    protected function rateDemonDiff(Level $level, int $rating): bool
    {
        if (empty($level->rating)) {
            return false;
        }

        $rt = $level->rating;
        $rt->demon_difficulty = $this->guessDemonDifficultyFromRating($rating);

        return $rt->save();
    }

    protected function guessDemonDifficultyFromRating(int $rating): LevelRatingDemonDifficulty
    {
        return match ($rating) {
            1 => LevelRatingDemonDifficulty::EASY,
            2 => LevelRatingDemonDifficulty::MEDIUM,
            4 => LevelRatingDemonDifficulty::INSANE,
            5 => LevelRatingDemonDifficulty::EXTREME,
            default => LevelRatingDemonDifficulty::HARD
        };
    }

    public function suggestStars(LevelSuggestStarRequest $request): int
    {
        $data = $request->validated();

        $level = Level::query()
            ->findOrFail($data['levelID']);

        foreach ($request->account->groups as $group) {
            if ($group->can('direct-rate')) {
                LevelRatingSuggestion::query()
                    ->where('level_id', $level->id)
                    ->delete();

                if (! $this->rate($level, $data['stars'])) {
                    return \App\Enums\Response::LEVEL_RATE_FAILED_UNKNOWN_ERROR->value;
                }

                $level->rating->featured_score = $data['feature'];
                $level->rating->save();

                return Response::LEVEL_RATE_SUCCESS->value;
            }
        }

        $record = LevelRatingSuggestion::query()
            ->where([
                'account_id' => $request->account->id,
                'level_id' => $level->id,
                'rating' => $data['stars'],
                'featured' => $data['feature'],
            ]);

        if (! $record->exists()) {
            $record = new LevelRatingSuggestion();
            $record->account_id = $request->account->id;
            $record->level_id = $data['levelID'];
            $record->rating = $data['stars'];
            $record->featured = $data['feature'];
            $record->save();

            return \App\Enums\Response::LEVEL_RATE_SUGGESTION_SUBMITTED->value;
        }

        return \App\Enums\Response::LEVEL_RATE_SUGGEST_FAILED_ALREADY_SUGGESTED->value;
    }

    protected function rate(Level $level, int $stars): bool
    {
        if (! empty($level->rating)) {
            return false;
        }

        $rating = new LevelRating();
        $rating->level_id = $level->id;
        $rating->stars = $stars;
        $rating->difficulty = HelperController::guessLevelRatingDifficultyFromStars($stars);
        $rating->auto = $rating->difficulty === LevelRatingDifficulty::AUTO;
        $rating->demon = $rating->difficulty === LevelRatingDifficulty::DEMON;

        return $rating->save();
    }
}
