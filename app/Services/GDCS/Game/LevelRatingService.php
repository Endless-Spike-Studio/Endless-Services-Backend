<?php

namespace App\Services\GDCS\Game;

use App\Models\GDCS\LevelRating;
use App\Models\GDCS\UserScore;

class LevelRatingService
{
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
