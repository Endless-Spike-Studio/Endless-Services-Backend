<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Models\PlayerQuest;
use App\EndlessServer\Requests\GameQuestGetRequest;
use App\EndlessServer\Services\GamePlayerQuestService;
use App\GeometryDash\Enums\GeometryDashSalts;
use App\GeometryDash\Enums\GeometryDashXorKeys;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use Base64Url\Base64Url;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

readonly class GameQuestController
{
	public function __construct(
		protected GamePlayerQuestService       $service,
		protected GeometryDashAlgorithmService $algorithmService
	)
	{

	}

	/**
	 * @throws EndlessServerGameException
	 */
	public function get(GameQuestGetRequest $request): string
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$this->service->initialize($player->id);

		$configCount = config('services.endless_server.quests.count');

		$now = now();
		$today = Carbon::today();

		$quests = PlayerQuest::query()
			->where('player_id', $player->id)
			->whereDate('created_at', $today)
			->limit($configCount)
			->get();

		if ($quests->count() < $configCount) {
			throw new EndlessServerGameException(
				__('任务获取失败: 数量异常')
			);
		}

		$result = Base64Url::encode($this->algorithmService->xor(implode(':', [
			Str::random(5),
			$player->id,
			$this->algorithmService->xor(Base64Url::decode(
				substr($data['chk'], 5)
			), GeometryDashXorKeys::CHALLENGE->value),
			$player->udid,
			$player->uuid,
			$now->secondsUntilEndOfDay(),
			...$quests->map(function (PlayerQuest $quest) {
				return implode(',', [
					$quest->id,
					$quest->collect_type,
					$quest->collect_count,
					$quest->reward_count,
					$quest->name
				]);
			})
		]), GeometryDashXorKeys::CHALLENGE->value), true);

		return implode('|', [
			Str::random(5) . $result,
			sha1($result . GeometryDashSalts::CHALLENGES_HASH->value)
		]);
	}
}