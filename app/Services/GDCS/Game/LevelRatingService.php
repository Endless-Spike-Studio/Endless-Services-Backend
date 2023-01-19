<?php

namespace App\Services\GDCS\Game;

use App\Models\GDCS\LevelRating;
use App\Models\GDCS\UserScore;

class LevelRatingService
{
    public static function reCalculateCreatorPoints(): void
    {
        $ratings = LevelRating::query()
            ->with('level.creator.score')
            ->get();

        UserScore::query()
            ->update([
                'creator_points' => 0
            ]);

        foreach ($ratings as $rating) {
            $amount = 0;

            if ($rating->stars > 0) {
                $amount += config('gdcn.game.creator_points.rated');
            }

            if ($rating->featured_score > 0) {
                $multiplyWithScore = config('gdcn.game.creator_points.featured.multiply_with_score');
                $featuredReward = config('gdcn.game.creator_points.featured.reward');

                if ($multiplyWithScore) {
                    $amount += $featuredReward * $rating->featured_score;
                } else {
                    $amount += $featuredReward;
                }
            }

            if ($rating->epic) {
                $amount += config('gdcn.game.creator_points.epic');
            }

            $rating->level
                ?->creator
                ?->score()
                ->increment('creator_points', $amount);
        }
    }
}
