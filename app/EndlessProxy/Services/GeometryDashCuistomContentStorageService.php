<?php

namespace App\EndlessProxy\Services;

use App\EndlessProxy\Exceptions\CustomContentResolveException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GeometryDashCuistomContentStorageService
{
	protected readonly string $disk;
	protected readonly string $format;

	public function __construct(
		protected readonly GeometryDashProxyService $proxy
	)
	{
		$this->disk = config('services.endless.proxy.geometry_dash.custom_contents.storage.disk');
		$this->format = config('services.endless.proxy.geometry_dash.custom_contents.storage.format');
	}

	public function raw(string $path): string
	{
		$this->fetch($path);

		$storage = Storage::disk($this->disk);
		$path = $this->toPath($path);

		return $storage->get($path);
	}

	public function fetch(string $path): bool
	{
		try {
			$storage = Storage::disk($this->disk);
			$path = $this->toPath($path);

			if ($this->valid($path)) {
				return true;
			}

			$upstream = Cache::rememberForever(sha1(
				implode('|', [__CLASS__, __FUNCTION__, 'upstream'])
			), function () {
				return $this->proxy
					->getRequest()
					->post('/getCustomContentURL.php')
					->body();
			});

			$data = $this->proxy
				->getRequest()
				->baseUrl($upstream)
				->get($path)
				->body();

			return $storage->put($path, $data);
		} catch (HttpClientException $e) {
			throw new CustomContentResolveException('请求异常', previous: $e);
		}
	}

	protected function toPath(string $path)
	{
		return str_replace('{path}', $path, $this->format);
	}

	public function valid(string $path): bool
	{
		$storage = Storage::disk($this->disk);
		$path = $this->toPath($path);

		return $storage->exists($path) && $storage->size($path) > 0;
	}

	public function url(string $path): string
	{
		$this->fetch($path);

		$storage = Storage::disk($this->disk);
		$path = $this->toPath($path);

		return $storage->url($path);
	}
}