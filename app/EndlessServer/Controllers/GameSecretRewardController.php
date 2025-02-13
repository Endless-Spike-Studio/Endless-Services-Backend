<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Models\SecretReward;
use App\EndlessServer\Models\SecretRewardRecord;
use App\EndlessServer\Requests\GameSecretRewardGetRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\GeometryDashSalts;
use App\GeometryDash\Enums\GeometryDashXorKeys;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

readonly class GameSecretRewardController
{
	public function __construct(
		protected GeometryDashAlgorithmService $algorithmService
	)
	{

	}

	public function get(GameSecretRewardGetRequest $request): string
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$secretReward = SecretReward::query()
			->where('code', $data['rewardKey'])
			->first();

		if ($secretReward->expires_at !== null) {
			$expired = $secretReward->expires_at->isPast();

			if ($expired) {
				return GeometryDashResponses::SECRET_REWARD_GET_FAILED_EXPIRED->value;
			}
		}

		if ($secretReward->limit !== null) {
			$count = $secretReward->records()
				->count();

			if ($count >= $secretReward->limit) {
				return GeometryDashResponses::SECRET_REWARD_GET_FAILED_LIMIT_REACHED->value;
			}
		}

		$rewards = collect($secretReward->rewards)
			->map(function ($value, $key) {
				return implode(',', [$key, $value]);
			})
			->join(',');

		SecretRewardRecord::query()
			->create([
				'player_id' => $player->id,
				'secret_reward_id' => $secretReward->id
			]);

		$result = Base64Url::encode($this->algorithmService->xor(implode(':', [
			Str::random(5),
			$this->algorithmService->xor(Base64Url::decode(
				substr($data['chk'], 5)
			), GeometryDashXorKeys::CHEST_REWARD->value),
			$secretReward->id,
			1,
			$rewards
		]), GeometryDashXorKeys::CHEST_REWARD->value), true);

		return implode('|', [
			Str::random(5) . $result,
			sha1($result . GeometryDashSalts::REWARDS_HASH->value)
		]);
	}
}