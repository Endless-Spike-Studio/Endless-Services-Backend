<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ProjectController extends Controller
{
    public function update(string $secret)
    {
        if (config('gdcn.updater.secret') !== $secret) {
            abort(403);
        }

        $result = [];
        $process = new Process(['vendor/bin/envoy', 'run', 'deploy-backend'], '/app');
        $process->setTimeout(600);

        $process->run(function ($type, $buffer) use (&$result) {
            $result[] = $buffer;
        });

        Log::channel('gdcn')
            ->info(__('gdcn.updater.action.project_updated'), [
                'output' => implode(PHP_EOL, $result)
            ]);
    }
}
