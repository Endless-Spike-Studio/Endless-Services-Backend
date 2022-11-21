<?php

namespace App\Listeners;

use App\Events\LevelRated;
use App\Jobs\GDCS\ReCalculateCreatorPointJob;

class LevelEventSubscriber
{
    public function handleLevelRated(): void
    {
        ReCalculateCreatorPointJob::dispatch();
    }

    public function subscribe(): array
    {
        return [
            LevelRated::class => 'handleLevelRated'
        ];
    }
}
