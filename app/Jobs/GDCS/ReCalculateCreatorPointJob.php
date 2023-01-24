<?php

namespace App\Jobs\GDCS;

use App\Services\Game\LevelRatingService as LevelRatingServiceAlias;
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
        LevelRatingServiceAlias::reCalculateCreatorPoints();
    }
}
