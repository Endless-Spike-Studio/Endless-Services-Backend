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
            $score = $rating->level
                ?->creator
                ?->score;

            if (empty($score)) {
                continue;
            }

            if ($rating->stars > 0) {
                $score->creator_points += config('gdcn.game.creator_points.rated', 1);
            }

            if ($rating->featured_score > 0) {
                $score->creator_points += config('gdcn.game.creator_points.featured.reward', 2) * (config('gdcn.game.creator_points.featured.multiply_with_score', false) ? $rating->featured_score : 1);
            }

            if ($rating->epic) {
                $score->creator_points += config('gdcn.game.creator_points.epic', 1);
            }

            $score->save();
        }
    }
}
