<?php

namespace App\Storage\Services;

use App\Storage\Exceptions\StorageException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StorageService
{
	/**
	 * @throws StorageException
	 */
	public function __construct(protected readonly array $storages)
	{
		foreach ($this->storages as $storage) {
			if (!Arr::has($storage, ['disk', 'format'])) {
				throw new StorageException(
					__('[存储] 配置不正确')
				);
			}
		}
	}

	public function allValid(array $data): bool
	{
		foreach ($this->storages as $storage) {
			$disk = Storage::disk($storage['disk']);
			$path = config('gdcn.storage.base') . '/' . $storage['format'];

			foreach ($data as $key => $value) {
				$path = Str::replace('{' . $key . '}', $value, $path);
			}

			if (!$disk->exists($path) || $disk->size($path) <= 0) {
				return false;
			}
		}

		return true;
	}

	public function exists(array $data): bool
	{
		foreach ($this->storages as $storage) {
			$disk = Storage::disk($storage['disk']);
			$path = config('gdcn.storage.base') . '/' . $storage['format'];

			foreach ($data as $key => $value) {
				$path = Str::replace('{' . $key . '}', $value, $path);
			}

			if ($disk->exists($path)) {
				return true;
			}
		}

		return false;
	}

	public function get(array $data): string
	{
		foreach ($this->storages as $storage) {
			$disk = Storage::disk($storage['disk']);
			$path = config('gdcn.storage.base') . '/' . $storage['format'];

			foreach ($data as $key => $value) {
				$path = Str::replace('{' . $key . '}', $value, $path);
			}

			if ($disk->exists($path)) {
				return $disk->get($path);
			}
		}

		throw new NotFoundHttpException();
	}

	public function allExists(array $data): bool
	{
		foreach ($this->storages as $storage) {
			$disk = Storage::disk($storage['disk']);
			$path = config('gdcn.storage.base') . '/' . $storage['format'];

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
			$path = config('gdcn.storage.base') . '/' . $storage['format'];

			foreach ($data as $key => $value) {
				$path = Str::replace('{' . $key . '}', $value, $path);
			}

			$disk->put($path, $content);
		}
	}

	public function delete(array $data): void
	{
		foreach ($this->storages as $storage) {
			$disk = Storage::disk($storage['disk']);
			$path = config('gdcn.storage.base') . '/' . $storage['format'];

			foreach ($data as $key => $value) {
				$path = Str::replace('{' . $key . '}', $value, $path);
			}

			if ($disk->exists($path)) {
				$disk->delete($path);
			}
		}
	}

	public function url(array $data): string
	{
		foreach ($this->storages as $storage) {
			$disk = Storage::disk($storage['disk']);
			$path = config('gdcn.storage.base') . '/' . $storage['format'];

			foreach ($data as $key => $value) {
				$path = Str::replace('{' . $key . '}', $value, $path);
			}

			if ($disk->exists($path)) {
				return $disk->url($path);
			}
		}

		throw new NotFoundHttpException();
	}

	public function download(array $data): StreamedResponse
	{
		foreach ($this->storages as $storage) {
			$disk = Storage::disk($storage['disk']);
			$path = config('gdcn.storage.base') . '/' . $storage['format'];

			foreach ($data as $key => $value) {
				$path = Str::replace('{' . $key . '}', $value, $path);
			}

			if ($disk->exists($path)) {
				return $disk->download($path);
			}
		}

		throw new NotFoundHttpException();
	}
}