<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class FrontendResourceUploadCommand extends Command
{
    protected $signature = 'frontend:upload-resources';
    protected $description = '上传前端文件到 oss';
    protected string $prefix = '/static/gdcn';
    protected Filesystem $storage;

    public function handle(): int
    {
        $this->storage = Storage::disk('oss');

        $bases = $this->option('bases');
        foreach (explode(',', $bases) as $base) {
            $this->storage->delete(
                $this->prefix . '/' . $base
            );

            $this->uploadDir(
                public_path($base),
                $base
            );
        }

        return 0;
    }

    protected function uploadDir(string $path, string $prefix): void
    {
        foreach (scandir($path) as $file) {
            if (in_array($file, ['.', '..'], true)) {
                continue;
            }

            $fullPath = $path . '/' . $file;
            $relativePath = $prefix . '/' . $file;

            if (is_dir($fullPath)) {
                $this->uploadDir($fullPath, $relativePath);
                continue;
            }

            $this->storage->put(
                $this->prefix . '/' . $relativePath,
                file_get_contents($fullPath)
            );

            $this->info($fullPath . ' => ' . $relativePath);
        }
    }
}
