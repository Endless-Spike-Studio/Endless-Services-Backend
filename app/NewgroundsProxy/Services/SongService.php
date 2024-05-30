<?php

namespace App\NewgroundsProxy\Services;

use App\GeometryDash\Exceptions\ResponseException;
use App\GeometryDash\Services\ObjectService;
use App\GeometryDash\Services\ResponseService;
use App\NewgroundsProxy\Entities\Song;
use App\NewgroundsProxy\Exceptions\SongDataFetchException;
use App\NewgroundsProxy\Exceptions\SongDisabledException;
use App\NewgroundsProxy\Exceptions\SongFetchException;
use App\Proxy\Services\ProxyService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SongService
{
	public function __construct(
		protected readonly SongStorageService $storage
	)
	{

	}

	/**
	 * @throws SongFetchException
	 * @throws SongDataFetchException
	 */
	public function download(int $id): StreamedResponse
	{
		$downloadable = $this->storage->allValid([
			'id' => $id
		]);

		if (!$downloadable) {
			$this->updateStorage($id);
		}

		return $this->storage->download([
			'id' => $id
		]);
	}

	/**
	 * @throws SongFetchException
	 * @throws SongDataFetchException
	 */
	protected function updateStorage(int $id): void
	{
		try {
			$song = $this->get($id);
			$url = urldecode($song->original_download_url);

			$request = ProxyService::instance()
				->timeout(0)
				->get($url);

			if (!$request->successful()) {
				throw new SongDataFetchException();
			}

			$data = $request->body();

			$this->storage->put([
				'id' => $id
			], $data);
		} catch (ConnectionException $e) {
			throw new SongDataFetchException(
				previous: $e
			);
		}
	}

	/**
	 * @throws SongFetchException
	 */
	public function get(int $id): Song
	{
		$song = Song::find($id);

		if (!empty($song)) {
			return $song;
		}

		$disabled = false;

		try {
			$object ??= $this->getFromOfficialSongApi($id);
		} catch (SongFetchException) {

		} catch (SongDisabledException) {
			$disabled = true;
		}

		try {
			$object ??= $this->getFromOfficialLevelApi($id);
		} catch (SongFetchException) {

		}

		if (empty($object) || !Arr::has($object, [1, 2, 3, 4, 5, 10])) {
			throw new SongFetchException();
		}

		return Song::create([
			'song_id' => $object[1],
			'name' => $object[2],
			'artist_id' => $object[3],
			'artist_name' => $object[4],
			'size' => $object[5],
			'disabled' => $disabled,
			'original_download_url' => $object[10]
		]);
	}

	/**
	 * @throws SongFetchException
	 * @throws SongDisabledException
	 */
	protected function getFromOfficialSongApi(int $id): array
	{
		try {
			$response = ProxyService::instance()
				->asForm()
				->withUserAgent(null)
				->post(config('gdcn.gdproxy.base') . '/getGJSongInfo.php', [
					'songID' => $id,
					'secret' => 'Wmfd2893gb7',
				])->body();

			if ($response === '-2') {
				throw new SongDisabledException();
			}

			ResponseService::validate($response);
			return ObjectService::split($response, '~|~');
		} catch (ConnectionException|ResponseException $e) {
			throw new SongFetchException(
				previous: $e
			);
		}
	}

	/**
	 * @throws SongFetchException
	 */
	protected function getFromOfficialLevelApi(int $id): array
	{
		try {
			$response = ProxyService::instance()
				->asForm()
				->withUserAgent(null)
				->post(config('gdcn.gdproxy.base') . '/getGJLevels21.php', [
					'song' => $id,
					'customSong' => true,
					'secret' => 'Wmfd2893gb7',
				])->body();

			ResponseService::validate($response);
			return ObjectService::split($response, '~|~');
		} catch (ConnectionException|ResponseException $e) {
			throw new SongFetchException(
				previous: $e
			);
		}
	}
}