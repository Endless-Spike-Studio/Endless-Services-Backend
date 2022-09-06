<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProjectUpdateCommand extends Command
{
    protected $signature = 'project:update';

    public function handle()
    {
        exec('/app/vendor/bin/envoy run deploy-backend');
    }
}
