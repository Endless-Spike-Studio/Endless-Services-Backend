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
        $content = Storage::disk('oss')->get('/static/gdcn/manifest.json');
        $fileName = public_path('build/manifest.json');

        $path = dirname($fileName);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        fwrite(fopen($fileName, 'w+'), $content);

        return Command::SUCCESS;
    }
}
