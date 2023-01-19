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
            $score = $rating->level?->creator?->score;

            if (empty($rating->level) || empty($score)) {
                continue;
            }

            $cp = 0;
            if ($rating->stars > 0) {
                $cp += config('gdcn.game.creator_points.rated');
            }

            if ($rating->featured_score > 0) {
                $multiplyWithScore = config('gdcn.game.creator_points.featured.multiply_with_score');
                $featuredReward = config('gdcn.game.creator_points.featured.reward');

                if ($multiplyWithScore) {
                    $cp += $featuredReward * $rating->featured_score;
                } else {
                    $cp += $featuredReward;
                }
            }

            if ($rating->epic) {
                $cp += config('gdcn.game.creator_points.epic');
            }

            $score->increment('creator_points', $cp);
        }
    }
}
