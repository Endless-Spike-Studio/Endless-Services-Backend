<?php

namespace App\Services;

use App\Exceptions\GDCS\InvalidStorageConfigException;
use App\Exceptions\StorageContentMissingException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function exists(string $value): bool
    {
        foreach ($this->storages as $storage) {
            $path = Str::replace('@', $value, $storage['format']);
            $disk = Storage::disk($storage['disk']);

            if ($disk->exists($path)) {
                return true;
            }
        }

        return false;
    }

    public function allExists(string $value): bool
    {
        foreach ($this->storages as $storage) {
            $path = Str::replace('@', $value, $storage['format']);
            $disk = Storage::disk($storage['disk']);

            if (!$disk->exists($path)) {
                return false;
            }
        }

        return true;
    }

    public function put(string $value, string $content): void
    {
        foreach ($this->storages as $storage) {
            $path = Str::replace('@', $value, $storage['format']);
            $disk = Storage::disk($storage['disk']);

            $disk->put($path, $content);
        }
    }

    /**
     * @throws StorageContentMissingException
     */
    public function download(string $value): StreamedResponse
    {
        foreach ($this->storages as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = Str::replace('@', $value, $storage['format']);

            if ($disk->exists($path)) {
                return $disk->download($path);
            }
        }

        throw new StorageContentMissingException();
    }
}
