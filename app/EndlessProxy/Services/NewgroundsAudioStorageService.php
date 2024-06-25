<?php

namespace App\EndlessProxy\Services;

use App\EndlessProxy\Exceptions\SongResolveException;
use App\EndlessProxy\Models\NewgroundsSong;
use Illuminate\Http\Client\ConnectionException;
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

	public function get(NewgroundsSong $song): string
	{
		$storage = Storage::disk($this->disk);
		$path = $this->toPath($song->song_id);

		$this->fetch($song);

		return $storage->get($path);
	}

	protected function toPath(int $id)
	{
		return str_replace('{id}', $id, $this->format);
	}

	public function fetch(NewgroundsSong $song): bool
	{
		try {
			$storage = Storage::disk($this->disk);
			$path = $this->toPath($song->song_id);

			if ($storage->exists($path) && $storage->size($path) > 0) {
				return true;
			}

			$url = urldecode($song->original_download_url);

			$data = $this->proxy->getRequest()
				->get($url)
				->body();

			return $storage->put($path, $data);
		} catch (ConnectionException $e) {
			throw new SongResolveException('链接异常', previous: $e);
		}
	}
}