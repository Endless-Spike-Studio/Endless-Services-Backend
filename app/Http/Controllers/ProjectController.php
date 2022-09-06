<?php

namespace App\Http\Controllers;

use Symfony\Component\Process\Process;

class ProjectController extends Controller
{
    public function update(): void
    {
        $this->dispatchSync(function () {
            $process = new Process(['vendor/bin/envoy', 'run', 'deploy-backend'], '/app');
            return $process->run();
        });
    }
}
