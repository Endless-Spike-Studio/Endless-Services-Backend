<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ProjectUpdateCommand extends Command
{
    protected $signature = 'project:update';

    public function handle(): int
    {
        $process = new Process(['/app/vendor/bin/envoy', 'run', 'deploy-backend']);
        return $process->run();
    }
}
