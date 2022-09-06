<?php

namespace App\Http\Controllers;

use Symfony\Component\Process\Process;

class ProjectController extends Controller
{
    public static function update(): int
    {
        $process = new Process(['/app/vendor/bin/envoy', 'run', 'deploy-backend']);
        return $process->run();
    }
}
