<?php

namespace App\Services\Game;

use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\LevelRatingDifficulty;
use App\Models\GDCS\LevelRating;
use App\Models\GDCS\UserScore;

class LevelRatingService
{
    public static function guessDifficultyFromStars(int $stars): LevelRatingDifficulty
    {
        return match ($stars) {
            1, => LevelRatingDifficulty::AUTO,
            2 => LevelRatingDifficulty::EASY,
            3 => LevelRatingDifficulty::NORMAL,
            4, 5 => LevelRatingDifficulty::HARD,
            6, 7 => LevelRatingDifficulty::HARDER,
            8, 9 => LevelRatingDifficulty::INSANE,
            10 => LevelRatingDifficulty::DEMON,
            default => LevelRatingDifficulty::NA
        };
    }

    public static function guessDemonDifficultyFromRating(int $rating): LevelRatingDemonDifficulty
    {
        return match ($rating) {
            1 => LevelRatingDemonDifficulty::EASY,
            2 => LevelRatingDemonDifficulty::MEDIUM,
            4 => LevelRatingDemonDifficulty::INSANE,
            5 => LevelRatingDemonDifficulty::EXTREME,
            default => LevelRatingDemonDifficulty::HARD
        };
    }

    public static function reCalculateCreatorPoints(): void
    {
        $relation = 'level.creator.score';

        UserScore::query()
            ->update([
                'creator_points' => 0
            ]);

        LevelRating::query()
            ->with($relation)
            ->whereHas($relation)
            ->each(function (LevelRating $rating) {
                $score = $rating->level
                    ?->creator
                    ?->score();

                if (empty($score)) {
                    return;
                }

                if ($rating->stars > 0) {
                    $amount = config('gdcn.game.creator_points.rated');
                    $score->increment('creator_points', $amount);
                }

                if ($rating->featured_score > 0) {
                    $multiplyWithScore = config('gdcn.game.creator_points.featured.multiply_with_score');
                    $featuredReward = config('gdcn.game.creator_points.featured.reward');

                    if ($multiplyWithScore) {
                        $score->increment('creator_points', $featuredReward * $rating->featured_score);
                    } else {
                        $score->increment('creator_points', $featuredReward);
                    }
                }

                if ($rating->epic) {
                    $amount = config('gdcn.game.creator_points.epic');
                    $score->increment('creator_points', $amount);
                }
            });
    }
}
