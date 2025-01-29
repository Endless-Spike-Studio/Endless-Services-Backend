<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Requests\GamePlayerDataUpdateRequest;
use App\EndlessServer\Services\GamePlayerDataService;
use App\EndlessServer\Services\GamePlayerStatisticService;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

readonly class GamePlayerDataController
{
	public function __construct(
		protected GamePlayerDataService      $service,
		protected GamePlayerStatisticService $statisticService
	)
	{

	}

	public function update(GamePlayerDataUpdateRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$this->service->update($player->id, $data['stars'], $data['moons'], $data['demons'], $data['diamonds'], $data['icon'], $data['iconType'], $data['coins'], $data['userCoins'], $data['accIcon'], $data['accShip'], $data['accBall'], $data['accBird'], $data['accDart'], $data['accRobot'], $data['accGlow'], $data['accSpider'], $data['accExplosion'], $data['accSwing'], $data['accJetpack'], $data['color1'], $data['color2'], $data['color3'], $data['special']);

		$this->statisticService->initialize($player->id);

		$sinfoParts = explode(',', $data['sinfo']);

		if (count($sinfoParts) === 12) {
			$this->statisticService->updateClassic($player->id, $sinfoParts[0], $sinfoParts[1], $sinfoParts[2], $sinfoParts[3], $sinfoParts[4], $sinfoParts[5]);
			$this->statisticService->updatePlatformer($player->id, $sinfoParts[6], $sinfoParts[7], $sinfoParts[8], $sinfoParts[9], $sinfoParts[10], $sinfoParts[11]);
		}

		if (!empty($data['dinfo'])) {
			$completedDemonLevelIds = explode(',', $data['dinfo']);
			$this->statisticService->updateDemon($player->id, $completedDemonLevelIds);
		}

		if (!isset($data['dinfow'])) {
			$data['dinfow'] = 0;
		}

		$this->statisticService->updateInterval($player->id, $data['sinfod'], $data['dinfow']);

		if (!isset($data['dinfog'])) {
			$data['dinfog'] = 0;
		}

		$this->statisticService->updateGauntlet($player->id, $data['sinfog'], $data['dinfog']);

		return GeometryDashResponses::PLAYER_DATA_UPDATE_SUCCESS->value;
	}
}