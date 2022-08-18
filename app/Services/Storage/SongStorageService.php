<?php

namespace App\Services\Storage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SongStorageService extends BaseStorageService
{
    public function __construct()
    {
        parent::__construct(
            config('gdcn.storages.songs')
        );
    }

    public function allValid(array $data): bool
    {
        foreach ($this->storages as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = $storage['format'];

            foreach ($data as $key => $value) {
                $path = Str::replace('{' . $key . '}', $value, $path);
            }

            if ($disk->size($path) <= 0) {
                return false;
            }
        }

        return true;
    }
}
