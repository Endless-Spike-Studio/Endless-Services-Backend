<?php

namespace App\EndlessProxy\Services;

use App\EndlessProxy\Exceptions\SongResolveException;
use App\EndlessProxy\Jobs\FetchSongDataJob;
use App\EndlessProxy\Models\NewgroundsSong;
use App\EndlessProxy\Objects\GameSongObject;
use App\GeometryDash\Enums\GeometryDashSecrets;
use App\GeometryDash\Enums\Objects\GeometryDashSongObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Arr;

readonly class NewgroundsAudioProxyService
{
	public function __construct(
		protected GeometryDashProxyService      $proxy,
		protected GeometryDashObjectService     $object,
		protected NewgroundsAudioStorageService $storage
	)
	{

	}

	public function resolve(int $id): NewgroundsSong
	{
		try {
			$song = NewgroundsSong::query()
				->where('song_id', $id)
				->first();

			if ($song === null) {
				$songObject = $this->resolveSongObjectFromOfficialServerLevelApi($id);

				if ($songObject === null) {
					throw new SongResolveException('解析失败, 该歌曲可能不存在');
				}

				$song = NewgroundsSong::query()
					->create([
						'song_id' => $songObject[GeometryDashSongObjectDefinitions::ID->value],
						'name' => $songObject[GeometryDashSongObjectDefinitions::NAME->value],
						'artist_id' => $songObject[GeometryDashSongObjectDefinitions::ARTIST_ID->value],
						'artist_name' => $songObject[GeometryDashSongObjectDefinitions::ARTIST_NAME->value],
						'size' => $songObject[GeometryDashSongObjectDefinitions::SIZE->value],
						'disabled' => false,
						'original_download_url' => $songObject[GeometryDashSongObjectDefinitions::DOWNLOAD_URL->value]
					]);
			}

			$this->storage->song = $song;

			return $song;
		} catch (HttpClientException $e) {
			throw new SongResolveException('请求异常', previous: $e);
		} finally {
			if (isset($song) && !$this->storage->valid()) {
				FetchSongDataJob::dispatch($song);
			}
		}
	}

	/**
	 * @throws ConnectionException
	 */
	protected function resolveSongObjectFromOfficialServerLevelApi(int $id): ?array
	{
		$songApiResult = $this->resolveSongObjectUsingOfficialServerSongApi($id);

		if ($this->validateSongObject($songApiResult)) {
			return $songApiResult;
		}

		$levelApiResult = $this->resolveSongObjectUsingOfficialServerLevelApi($id);

		if ($this->validateSongObject($levelApiResult)) {
			return $levelApiResult;
		}

		return null;
	}

	/**
	 * @throws ConnectionException
	 */
	protected function resolveSongObjectUsingOfficialServerSongApi(int $id): array
	{
		$response = $this->proxy->getRequest()
			->post('getGJSongInfo.php', [
				'songID' => $id,
				'secret' => GeometryDashSecrets::COMMON->value
			])
			->body();

		if ($response === '-2') {
			NewgroundsSong::created(function (NewgroundsSong $song) use ($id) {
				if ($song->song_id == $id) {
					$song->update([
						'disabled' => true
					]);
				}
			});
		}

		return $this->object->split($response, GeometryDashSongObjectDefinitions::GLUE);
	}

	protected function validateSongObject(array $object): bool
	{
		return Arr::has($object, [
			GeometryDashSongObjectDefinitions::ID->value,
			GeometryDashSongObjectDefinitions::NAME->value,
			GeometryDashSongObjectDefinitions::ARTIST_ID->value,
			GeometryDashSongObjectDefinitions::ARTIST_NAME->value,
			GeometryDashSongObjectDefinitions::SIZE->value,
			GeometryDashSongObjectDefinitions::DOWNLOAD_URL->value,
		]);
	}

	/**
	 * @throws ConnectionException
	 */
	protected function resolveSongObjectUsingOfficialServerLevelApi(int $id): array
	{
		$response = $this->proxy->getRequest()
			->post('getGJLevels21.php', [
				'song' => $id,
				'customSong' => true,
				'secret' => GeometryDashSecrets::COMMON->value
			])
			->body();

		return $this->object->split(Arr::get(explode('#', $response), 2), GeometryDashSongObjectDefinitions::GLUE);
	}

	public function toObject(NewgroundsSong $song): string
	{
		return new GameSongObject($song)->merge();
	}
}