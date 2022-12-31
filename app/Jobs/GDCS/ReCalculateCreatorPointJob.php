<?php

namespace App\Jobs\GDCS;

use App\Models\GDCS\LevelRating;
use App\Models\GDCS\UserScore;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReCalculateCreatorPointJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
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

            if ($rating->stars > 0) {
                $score->creator_points += config('gdcn.game.creator_points.rated');
            }

            if ($rating->featured_score > 0) {
                $multiplyWithScore = config('gdcn.game.creator_points.featured.multiply_with_score');
                $featuredReward = config('gdcn.game.creator_points.featured.reward');

                if ($multiplyWithScore) {
                    $score->creator_points += $featuredReward * $rating->featured_score;
                } else {
                    $score->creator_points += $featuredReward;
                }
            }

            if ($rating->epic) {
                $score->creator_points += config('gdcn.game.creator_points.epic');
            }

            $score->save();
        }
    }
}
