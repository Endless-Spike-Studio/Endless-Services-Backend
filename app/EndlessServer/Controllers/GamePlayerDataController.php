<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Models\PlayerDemonRecord;
use App\EndlessServer\Requests\GamePlayerDataUpdateRequest;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelRatingDemonDifficulties;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

class GamePlayerDataController
{
	public function update(GamePlayerDataUpdateRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$player->data()
			->updateOrCreate([
				'stars' => $data['stars'],
				'moons' => $data['moons'],
				'demons' => $data['demons'],
				'diamonds' => $data['diamonds'],
				'icon_id' => $data['icon'],
				'icon_type' => $data['iconType'],
				'coins' => $data['coins'],
				'user_coins' => $data['userCoins'],
				'cube_id' => $data['accIcon'],
				'ship_id' => $data['accShip'],
				'ball_id' => $data['accBall'],
				'bird_id' => $data['accBird'],
				'dart_id' => $data['accDart'],
				'robot_id' => $data['accRobot'],
				'glow_id' => $data['accGlow'],
				'spider_id' => $data['accSpider'],
				'explosion_id' => $data['accExplosion'],
				'swing_id' => $data['accSwing'],
				'jetpack_id' => $data['accJetpack'],
				'color1' => $data['color1'],
				'color2' => $data['color2'],
				'color3' => $data['color3'],
				'special' => $data['special']
			]);

		$sinfoParts = explode(',', $data['sinfo']);

		if (count($sinfoParts) < 12) {
			return GeometryDashResponses::PLAYER_DATA_UPDATE_FAILED_INVALID_SINFO->value;
		}

		$completedDemonLevelIds = explode(',', $data['dinfo']);

		Level::query()
			->whereIn('id', $completedDemonLevelIds)
			->pluck('id')
			->map(function (int $id) use ($player) {
				PlayerDemonRecord::query()
					->updateOrCreate([
						'player_id' => $player->id,
						'level_id' => $id
					]);
			});

		$player->statistic()
			->updateOrCreate([
				'completed_dailies_count' => $data['sinfod'],
				'completed_weeklies_count' => $data['dinfow'],
				'completed_classic_auto_count' => $sinfoParts[0],
				'completed_classic_easy_count' => $sinfoParts[1],
				'completed_classic_normal_count' => $sinfoParts[2],
				'completed_classic_hard_count' => $sinfoParts[3],
				'completed_classic_harder_count' => $sinfoParts[4],
				'completed_classic_insane_count' => $sinfoParts[5],
				'completed_platformer_auto_count' => $sinfoParts[6],
				'completed_platformer_easy_count' => $sinfoParts[7],
				'completed_platformer_normal_count' => $sinfoParts[8],
				'completed_platformer_hard_count' => $sinfoParts[9],
				'completed_platformer_harder_count' => $sinfoParts[10],
				'completed_platformer_insane_count' => $sinfoParts[11],

				'completed_classic_easy_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EASY->value);
					})
					->count(),
				'completed_classic_medium_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::MEDIUM->value);
					})
					->count(),
				'completed_classic_hard_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::HARD->value);
					})
					->count(),
				'completed_classic_insane_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::INSANE->value);
					})
					->count(),
				'completed_classic_extreme_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EXTREME->value);
					})
					->count(),

				'completed_platformer_easy_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EASY->value);
					})
					->count(),
				'completed_platformer_medium_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::MEDIUM->value);
					})
					->count(),
				'completed_platformer_hard_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::HARD->value);
					})
					->count(),
				'completed_platformer_insane_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::INSANE->value);
					})
					->count(),
				'completed_platformer_extreme_demons_count' => Level::query()
					->whereIn('id', $completedDemonLevelIds)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EXTREME->value);
					})
					->count(),

				'completed_gauntlet_levels_count' => $data['sinfog'],
				'completed_gauntlet_demon_levels_count' => $data['dinfog']
			]);

		return GeometryDashResponses::PLAYER_DATA_UPDATE_SUCCESS->value;
	}
}