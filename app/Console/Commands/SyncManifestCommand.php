<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncManifestCommand extends Command
{
    protected $signature = 'manifest:sync';

    protected $description = 'Sync manifest file';

    public function handle(): int
    {
        $content = Storage::disk('oss')->get('/static/website/manifest.json');
        fwrite(fopen(public_path('build/manifest.json'), 'w+'), $content);

        return Command::SUCCESS;
    }
}
