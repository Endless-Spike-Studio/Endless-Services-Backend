<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class UpdateProjectJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $result = [];
        $process = new Process(['vendor/bin/envoy', 'run', 'clean-logs'], '/app');
        $process->setTimeout(600);

        $process->run(function ($type, $buffer) use (&$result) {
            $result[] = $buffer;
        });

        Log::channel('gdcn')
            ->info(__('project_updated'), [
                'output' => implode(PHP_EOL, $result)
            ]);
    }
}
