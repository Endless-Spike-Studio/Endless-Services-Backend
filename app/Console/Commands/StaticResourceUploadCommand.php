<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Async\Pool;

class StaticResourceUploadCommand extends Command
{
    protected $signature = 'static:upload';
    protected string $prefix = '/static/gdcn/build';

    public function handle()
    {
        $this->process(
            public_path('build')
        );
    }

    protected function process(string $path, string $prefix = null)
    {
        $pool = Pool::create();

        $storage = Storage::disk('oss');
        $storage->deleteDirectory($this->prefix);

        foreach (scandir($path) as $file) {
            if (in_array($file, ['.', '..'], true)) {
                continue;
            }

            $fullPath = $path . '/' . $file;
            $relativePath = $prefix . '/' . $file;

            if (is_dir($fullPath)) {
                $this->process($fullPath, $relativePath);
                continue;
            }

            $pool[] = async(function () use ($storage, $relativePath, $fullPath) {
                return $storage->put(
                    $this->prefix . '/' . $relativePath,
                    file_get_contents($fullPath)
                );
            })->then(function () use ($relativePath, $fullPath) {
                $this->info($fullPath . ' => ' . $this->prefix . $relativePath);
            });
        }

        await($pool);
    }
}
