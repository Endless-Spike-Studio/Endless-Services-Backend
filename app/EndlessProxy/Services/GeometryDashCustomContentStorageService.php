<?php

namespace App\EndlessProxy\Services;

use App\EndlessProxy\Contracts\ExternalProxyStorageServiceContract;
use App\EndlessProxy\Exceptions\CustomContentResolveException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GeometryDashCustomContentStorageService implements ExternalProxyStorageServiceContract
{
	public string $path {
		set {
			$this->path = $this->format;
			$this->path = Str::replace('{path}', $value, $this->path);
		}
	}
	protected string $disk;
	protected string $format;
	protected Filesystem $storage;

	public function __construct(
		protected readonly GeometryDashProxyService $proxy
	)
	{
		$this->disk = config('services.endless.proxy.geometry_dash.custom_contents.storage.disk');
		$this->format = config('services.endless.proxy.geometry_dash.custom_contents.storage.format');

		$this->storage = Storage::disk($this->disk);
	}

	public function download(): StreamedResponse
	{
		$this->fetch();

		return $this->storage->download($this->path);
	}

	public function fetch(): bool
	{
		try {
			if ($this->valid()) {
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

			Log::debug(implode('|', [__CLASS__, __FUNCTION__, 'upstream']), [
				'base' => $upstream
			]);

			$data = $this->proxy
				->getRequest()
				->baseUrl($upstream)
				->get($this->path)
				->body();

			return $this->storage->put($this->path, $data);
		} catch (HttpClientException $e) {
			throw new CustomContentResolveException('请求异常', previous: $e);
		}
	}

	public function valid(): bool
	{
		return $this->storage->exists($this->path) && $this->storage->size($this->path) > 0;
	}
}