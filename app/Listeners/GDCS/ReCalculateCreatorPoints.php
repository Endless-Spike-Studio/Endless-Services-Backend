<?php

namespace App\Listeners\GDCS;

use App\Jobs\GDCS\ReCalculateCreatorPointJob;

class ReCalculateCreatorPoints
{
    public function handle(): void
    {
        ReCalculateCreatorPointJob::dispatch();
    }
}
