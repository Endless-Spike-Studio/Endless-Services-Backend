<?php

namespace App\Services\Storage;

use App\Exceptions\StorageException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BaseStorageService
{
    /**
     * @throws StorageException
     */
    public function __construct(protected array $storages)
    {
        foreach ($this->storages as $storage) {
            if (!Arr::has($storage, ['disk', 'format'])) {
                throw new StorageException(__('error.invalid_config'));
            }
        }
    }

    /**
     * @throws StorageException
     */
    public function get(array $data): string
    {
        foreach ($this->storages as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = $storage['format'];

            foreach ($data as $key => $value) {
                $path = Str::replace('{' . $key . '}', $value, $path);
            }

            if ($disk->exists($path)) {
                return $disk->get($path);
            }
        }

        throw StorageException::notFound();
    }

    public function exists(array $data): bool
    {
        foreach ($this->storages as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = $storage['format'];

            foreach ($data as $key => $value) {
                $path = Str::replace('{' . $key . '}', $value, $path);
            }

            if ($disk->exists($path)) {
                return true;
            }
        }

        return false;
    }

    public function allExists(array $data): bool
    {
        foreach ($this->storages as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = $storage['format'];

            foreach ($data as $key => $value) {
                $path = Str::replace('{' . $key . '}', $value, $path);
            }

            if (!$disk->exists($path)) {
                return false;
            }
        }

        return true;
    }

    public function put(array $data, string $content): void
    {
        foreach ($this->storages as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = $storage['format'];

            foreach ($data as $key => $value) {
                $path = Str::replace('{' . $key . '}', $value, $path);
            }

            $disk->put($path, $content);
        }
    }

    /**
     * @throws StorageException
     */
    public function download(array $data): StreamedResponse
    {
        foreach ($this->storages as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = $storage['format'];

            foreach ($data as $key => $value) {
                $path = Str::replace('{' . $key . '}', $value, $path);
            }

            if ($disk->exists($path)) {
                return $disk->download($path);
            }
        }

        throw StorageException::notFound();
    }
}
