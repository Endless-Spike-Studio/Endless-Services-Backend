<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class StaticFilesUploadCommand extends Command
{
    protected $signature = 'static:upload {--bases=build}';
    protected $description = 'Upload static files';

    protected string $disk = 'oss';
    protected string $prefix = '/static';
    protected array $ignore = ['.', '..'];
    protected FilesystemAdapter|Filesystem $storage;

    public function handle(): int
    {
        $this->storage = Storage::disk($this->disk);

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

    protected function uploadDir(string $path, string $base): void
    {
        foreach (scandir($path) as $file) {
            if (in_array($file, $this->ignore, true)) {
                continue;
            }

            $fullPath = $path . '/' . $file;
            $relativePath = $base . '/' . $file;

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
