<?php

namespace App\EndlessProxy\Services;

use App\EndlessProxy\Controllers\GameCustomContentProxyController;
use App\EndlessProxy\Exceptions\SongResolveException;
use App\EndlessProxy\Jobs\FetchSongDataJob;
use App\EndlessProxy\Models\NewgroundsSong;
use App\GeometryDash\Enums\SpecialSongDownloadUrl;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

readonly class NewgroundsAudioStorageService
{
	protected string $disk;
	protected string $format;

	public function __construct(
		protected GeometryDashProxyService $proxy
	)
	{
		$this->disk = config('services.endless.proxy.newgrounds.audios.storage.disk');
		$this->format = config('services.endless.proxy.newgrounds.audios.storage.format');
	}

	public function raw(NewgroundsSong $song): string
	{
		FetchSongDataJob::dispatch($song)->onConnection('sync');

		$storage = Storage::disk($this->disk);
		$path = $this->toPath($song->song_id);

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

	public function valid(NewgroundsSong $song): bool
	{
		if ($this->checkCustom($song->original_download_url)) {
			return true;
		}

		$storage = Storage::disk($this->disk);
		$path = $this->toPath($song->song_id);

		return $storage->exists($path) && $storage->size($path) > 0;
	}

	protected function checkCustom(string $url): bool
	{
		return $url === SpecialSongDownloadUrl::CUSTOM->value;
	}

	public function url(NewgroundsSong $song): string
	{
		if ($this->checkCustom($song->original_download_url)) {
			return URL::action([GameCustomContentProxyController::class, 'handle'], [
				'path' => "music/$song->song_id.ogg"
			]);
		}

		FetchSongDataJob::dispatch($song)->onConnection('sync');

		$storage = Storage::disk($this->disk);
		$path = $this->toPath($song->song_id);

		return $storage->url($path);
	}
}