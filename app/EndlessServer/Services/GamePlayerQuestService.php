<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\EndlessServer\Models\PlayerQuest;
use App\GeometryDash\Enums\GeometryDashQuestCollectTypes;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Random\Randomizer;

class GamePlayerQuestService
{
	/**
	 * @throws EndlessServerGameException
	 */
	public function initialize(int $playerId): void
	{
		$configCount = config('services.endless.server.quests.count');

		$today = Carbon::today();

		$count = PlayerQuest::query()
			->where('player_id', $playerId)
			->whereDate('created_at', $today)
			->count();

		if ($count >= $configCount) {
			return;
		}

		$collectTypes = GeometryDashQuestCollectTypes::cases();

		$randomizer = new Randomizer();

		for ($i = 0; $i < $configCount - $count; $i++) {
			$type = Arr::random($collectTypes);

			if ($type === null) {
				throw new EndlessServerGameException(
					__('任务生成失败: 类型获取失败')
				);
			}

			$availableNames = config('services.endless.server.quests.' . $type->value . '.names');

			$name = null;

			if (count($availableNames) > 0) {
				$name = Arr::random($availableNames);
			}

			if ($name === null) {
				$typeName = Str::lower($type->name);

				$name = implode(' ', [
					...array_fill(0, 3, $typeName),
					'!'
				]);
			}

			$collectCount = $randomizer->getInt(
				config('services.endless.server.quests.' . $type->value . '.collect.min'),
				config('services.endless.server.quests.' . $type->value . '.collect.max')
			);

			$rewardCount = round(
				$collectCount / config('services.endless.server.quests.' . $type->value . '.reward.every') * config('services.endless.server.quests.' . $type->value . '.reward.give')
			);

			PlayerQuest::query()
				->create([
					'player_id' => $playerId,
					'name' => $name,
					'collect_type' => $type,
					'collect_count' => $collectCount,
					'reward_count' => $rewardCount
				]);
		}
	}
}