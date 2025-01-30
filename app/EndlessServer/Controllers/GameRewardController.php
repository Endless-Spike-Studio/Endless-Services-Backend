<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Models\PlayerChestRecord;
use App\EndlessServer\Requests\GameRewardGetRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\GeometryDashRewardTypes;
use App\GeometryDash\Enums\GeometryDashSalts;
use App\GeometryDash\Enums\GeometryDashXorKeys;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Random\Randomizer;

readonly class GameRewardController
{
	public function __construct(
		protected GeometryDashAlgorithmService $algorithmService
	)
	{

	}

	public function get(GameRewardGetRequest $request): int|string
	{
		$data = $request->validated();

		$randomizer = new Randomizer();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$lastSmallChestRecord = PlayerChestRecord::query()
			->where('player_id', $player->id)
			->where('type', GeometryDashRewardTypes::SMALL->value)
			->latest()
			->first();

		$smallChestRemainTime = 0;

		if ($lastSmallChestRecord !== null) {
			$smallChestRemainTime = max(-$lastSmallChestRecord->created_at->addSeconds(
				config('services.endless.server.rewards.small.wait')
			)->diffInSeconds(), 0);
		}

		$lastBigChestRecord = PlayerChestRecord::query()
			->where('player_id', $player->id)
			->where('type', GeometryDashRewardTypes::BIG->value)
			->latest()
			->first();

		$bigChestRemainTime = 0;

		if ($lastBigChestRecord !== null) {
			$bigChestRemainTime = max(-$lastBigChestRecord->created_at->addSeconds(
				config('services.endless.server.rewards.big.wait')
			)->diffInSeconds(), 0);
		}

		$emptyReward = implode(',', [0, 0, 0, 0]);

		switch ($data['rewardType']) {
			case GeometryDashRewardTypes::GET->value:
				$smallChestReward = $emptyReward;
				$bigChestReward = $emptyReward;
				break;
			case GeometryDashRewardTypes::SMALL->value:
				if ($bigChestRemainTime <= 0) {
					$smallChestRemainTime = config('services.endless.server.rewards.small.wait');

					$orbs = $randomizer->getInt(
						config('services.endless.server.rewards.small.orbs.min'),
						config('services.endless.server.rewards.small.orbs.max')
					);

					$diamonds = $randomizer->getInt(
						config('services.endless.server.rewards.small.diamonds.min'),
						config('services.endless.server.rewards.small.diamonds.max')
					);

					$shards = $randomizer->getInt(
						config('services.endless.server.rewards.small.shards.min'),
						config('services.endless.server.rewards.small.shards.max')
					);

					$keys = $randomizer->getInt(
						config('services.endless.server.rewards.small.keys.min'),
						config('services.endless.server.rewards.small.keys.max')
					);

					$smallChestReward = implode(',', [$orbs, $diamonds, $shards, $keys]);

					PlayerChestRecord::query()
						->create([
							'player_id' => $player->id,
							'type' => GeometryDashRewardTypes::SMALL->value,
							'orbs' => $orbs,
							'diamonds' => $diamonds,
							'shards' => $shards,
							'keys' => $keys
						]);
				} else {
					$smallChestReward = $emptyReward;
				}

				$bigChestReward = $emptyReward;
				break;
			case GeometryDashRewardTypes::BIG->value:
				$smallChestReward = $emptyReward;

				if ($bigChestRemainTime <= 0) {
					$bigChestRemainTime = config('services.endless.server.rewards.big.wait');

					$orbs = $randomizer->getInt(
						config('services.endless.server.rewards.big.orbs.min'),
						config('services.endless.server.rewards.big.orbs.max')
					);

					$diamonds = $randomizer->getInt(
						config('services.endless.server.rewards.big.diamonds.min'),
						config('services.endless.server.rewards.big.diamonds.max')
					);

					$shards = $randomizer->getInt(
						config('services.endless.server.rewards.big.shards.min'),
						config('services.endless.server.rewards.big.shards.max')
					);

					$keys = $randomizer->getInt(
						config('services.endless.server.rewards.big.keys.min'),
						config('services.endless.server.rewards.big.keys.max')
					);

					$bigChestReward = implode(',', [$orbs, $diamonds, $shards, $keys]);

					PlayerChestRecord::query()
						->create([
							'player_id' => $player->id,
							'type' => GeometryDashRewardTypes::BIG->value,
							'orbs' => $orbs,
							'diamonds' => $diamonds,
							'shards' => $shards,
							'keys' => $keys
						]);
				} else {
					$bigChestReward = $emptyReward;
				}
				break;
			default:
				return GeometryDashResponses::REWARD_GET_FAILED_INVALID_TYPE->value;
		}

		$result = Base64Url::encode($this->algorithmService->xor(implode(':', [
			Str::random(5),
			$player->id,
			$this->algorithmService->xor(Base64Url::decode(
				substr($data['chk'], 5)
			), GeometryDashXorKeys::CHEST_REWARD->value),
			$player->udid,
			$player->uuid,
			$smallChestRemainTime,
			$smallChestReward,
			PlayerChestRecord::query()
				->where('player_id', $player->id)
				->where('type', GeometryDashRewardTypes::SMALL->value)
				->count(),
			$bigChestRemainTime,
			$bigChestReward,
			PlayerChestRecord::query()
				->where('player_id', $player->id)
				->where('type', GeometryDashRewardTypes::BIG->value)
				->count(),
			$data['rewardType']
		]), GeometryDashXorKeys::CHEST_REWARD->value), true);

		return implode('|', [
			Str::random(5) . $result,
			sha1($result . GeometryDashSalts::REWARDS_HASH->value)
		]);
	}
}