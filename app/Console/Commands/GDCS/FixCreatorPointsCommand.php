<?php

namespace App\Console\Commands\GDCS;

use App\Jobs\GDCS\ReCalculateCreatorPointJob;
use Illuminate\Console\Command;

class FixCreatorPointsCommand extends Command
{
    protected $signature = 'GDCS:fix_cps';

    public function handle()
    {
        ReCalculateCreatorPointJob::dispatchSync();
    }
}
