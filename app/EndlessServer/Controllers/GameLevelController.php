<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Requests\GameLevelUpdateDescriptionRequest;
use App\EndlessServer\Requests\GameLevelUploadRequest;
use App\EndlessServer\Services\GameLevelDataStorageService;
use App\GeometryDash\Enums\GeometryDashResponses;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Auth;

readonly class GameLevelController
{
	public function __construct(
		protected GameLevelDataStorageService $storageService
	)
	{

	}

	public function upload(GameLevelUploadRequest $request)
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		if (!isset($data['original'])) {
			$data['original'] = 0;
		}

		if (!isset($data['extraString'])) {
			$data['extraString'] = '';
		}

		$description = null;

		if ($data['levelDesc'] !== null) {
			$description = Base64Url::decode($data['levelDesc']);
		}

		$attributes = [
			'player_id' => $player->id,
			'name' => $data['levelName'],
			'description' => $description,
			'version' => $data['levelVersion'],
			'length' => $data['levelLength'],
			'audio_track_id' => $data['audioTrack'],
			'password' => $data['password'],
			'original_level_id' => $data['original'],
			'2p_mode' => $data['twoPlayer'],
			'objects' => $data['objects'],
			'coins' => $data['coins'],
			'requested_stars' => $data['requestedStars'],
			'unlisted_type' => $data['unlisted'],
			'ldm_mode' => $data['ldm'],
			'editor_time' => $data['wt'],
			'previous_editor_time' => $data['wt2'],
			'extra' => $data['extraString'],
			'replay' => $data['levelInfo'],
			'verification_time' => $data['ts']
		];

		if (isset($data['levelID'])) {
			$level = Level::query()
				->where('player_id', $player->id)
				->where('id', $data['levelID'])
				->first();

			if ($level === null) {
				return GeometryDashResponses::LEVEL_UPLOAD_FAILED_NOT_FOUND->value;
			}

			unset($attributes['player_id']);

			$level->update($attributes);
		} else {
			$level = Level::query()
				->create($attributes);
		}

		$this->storageService->level = $level;
		$this->storageService->store($data['levelString']);

		return $level->id;
	}

	public function updateDescription(GameLevelUpdateDescriptionRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$level = Level::query()
			->where('player_id', $player->id)
			->where('id', $data['levelID'])
			->first();

		if ($level === null) {
			return GeometryDashResponses::LEVEL_UPLOAD_DESCRIPTION_FAILED_LEVEL_NOT_FOUND->value;
		}

		$description = null;

		if ($data['levelDesc'] !== null) {
			$description = Base64Url::decode($data['levelDesc']);
		}

		$level->update([
			'description' => $description
		]);

		return GeometryDashResponses::LEVEL_UPLOAD_DESCRIPTION_SUCCESS->value;
	}
}