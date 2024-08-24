<?php

namespace App\EndlessProxy\Services;

use App\EndlessProxy\Exceptions\SongResolveException;
use App\EndlessProxy\Models\NewgroundsSong;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Storage;

class NewgroundsAudioStorageService
{
	protected readonly string $disk;
	protected readonly string $format;

	public function __construct(
		protected readonly GeometryDashProxyService $proxy
	)
	{
		$this->disk = config('services.endless.proxy.newgrounds.audios.storage.disk');
		$this->format = config('services.endless.proxy.newgrounds.audios.storage.format');
	}

	public function raw(NewgroundsSong $song): string
	{
		$this->fetch($song);

		$storage = Storage::disk($this->disk);
		$path = $this->toPath($song->song_id);

		return $storage->get($path);
	}

	public function fetch(NewgroundsSong $song): bool
	{
		try {
			$storage = Storage::disk($this->disk);
			$path = $this->toPath($song->song_id);

			if ($this->valid($song)) {
				return true;
			}

			$url = urldecode($song->original_download_url);

			$data = $this->proxy->getRequest()
				->get($url)
				->body();

			return $storage->put($path, $data);
		} catch (HttpClientException $e) {
			throw new SongResolveException('请求异常', previous: $e);
		}
	}

	protected function toPath(int $id)
	{
		return str_replace('{id}', $id, $this->format);
	}

	public function valid(NewgroundsSong $song): bool
	{
		$storage = Storage::disk($this->disk);
		$path = $this->toPath($song->song_id);

		return $storage->exists($path) && $storage->size($path) > 0;
	}

	public function url(NewgroundsSong $song): string
	{
		$this->fetch($song);

		$storage = Storage::disk($this->disk);
		$path = $this->toPath($song->song_id);

		return $storage->url($path);
	}
}