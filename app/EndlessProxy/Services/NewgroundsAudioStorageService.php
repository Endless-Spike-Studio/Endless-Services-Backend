<?php

namespace App\EndlessProxy\Services;

use App\EndlessProxy\Contracts\ExternalProxyStorageServiceContract;
use App\EndlessProxy\Exceptions\SongResolveException;
use App\EndlessProxy\Models\NewgroundsSong;
use App\GeometryDash\Enums\SpecialSongDownloadUrl;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewgroundsAudioStorageService implements ExternalProxyStorageServiceContract
{
	protected string $disk;
	protected string $format;

	protected Filesystem $storage;
	protected string $path;

	public NewgroundsSong $song {
		set {
			$this->song = $value;

			$this->path = $this->format;
			$this->path = str_replace('{id}', $this->song->song_id, $this->path);
		}
	}

	public function __construct(
		protected GeometryDashProxyService $proxy
	)
	{
		$this->disk = config('services.endless.proxy.newgrounds.audios.storage.disk');
		$this->format = config('services.endless.proxy.newgrounds.audios.storage.format');

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

			$url = urldecode($this->song->original_download_url);

			$data = $this->proxy->getRequest()
				->get($url)
				->body();

			return $this->storage->put($this->path, $data);
		} catch (HttpClientException $e) {
			throw new SongResolveException('请求异常', previous: $e);
		}
	}

	public function valid(): bool
	{
		if (!$this->shouldProcess()) {
			return true;
		}

		return $this->storage->exists($this->path) && $this->storage->size($this->path) > 0;
	}

	protected function shouldProcess(): bool
	{
		return $this->song->download_url !== SpecialSongDownloadUrl::CUSTOM->value;
	}
}