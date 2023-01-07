<?php

namespace App\Console\Commands;

use GuzzleHttp\Promise\Promise;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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

            $promise = new Promise(function () use ($storage, $relativePath, $fullPath, &$promise) {
                $storage->put(
                    $this->prefix . '/' . $relativePath,
                    file_get_contents($fullPath)
                );

                $promise->resolve(true);
            });

            $this->info($fullPath . ' => ' . $this->prefix . $relativePath);
        }
    }
}
