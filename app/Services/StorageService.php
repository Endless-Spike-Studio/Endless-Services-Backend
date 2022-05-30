<?php

namespace App\Services;

use App\Exceptions\GDCS\InvalidStorageConfigException;
use App\Exceptions\StorageContentMissingException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    /**
     * @throws InvalidStorageConfigException
     */
    public function __construct(protected array $storages)
    {
        foreach ($this->storages as $storage) {
            if (!Arr::has($storage, ['disk', 'format'])) {
                throw new InvalidStorageConfigException();
            }
        }
    }

    /**
     * @throws InvalidStorageConfigException
     */
    public static function parseFromConfig(string $key): StorageService
    {
        return new static(
            Config::get($key)
        );
    }

    /**
     * @throws StorageContentMissingException
     */
    public function get(string $value): string
    {
        foreach ($this->storages as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = Str::replace('@', $value, $storage['format']);

            if ($disk->exists($path)) {
                return $disk->get($path);
            }
        }

        throw new StorageContentMissingException();
    }

    public function put(string $value, string $content): void
    {
        foreach ($this->storages as $storage) {
            $path = Str::replace('@', $value, $storage['format']);
            $disk = Storage::disk($storage['disk']);

            $disk->put($path, $content);
        }
    }
}
