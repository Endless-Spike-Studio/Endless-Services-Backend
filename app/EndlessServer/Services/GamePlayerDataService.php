<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Models\PlayerData;

readonly class GamePlayerDataService
{
	public function updateVersions(int $playerId, int $gameVersion, int $binaryVersion): void
	{
		PlayerData::query()
			->where('player_id', $playerId)
			->update([
				'game_version' => $gameVersion,
				'binary_version' => $binaryVersion
			]);
	}

	public function update(int $playerId, int $stars, int $moons, int $demons, int $diamonds, int $icon_id, int $icon_type, int $coins, int $user_coins, int $cube_id, int $ship_id, int $ball_id, int $bird_id, int $dart_id, int $robot_id, int $glow_id, int $spider_id, int $explosion_id, int $swing_id, int $jetpack_id, int $color1, int $color2, int $color3, int $special): void
	{
		PlayerData::query()
			->where('player_id', $playerId)
			->update([
				'stars' => $stars,
				'moons' => $moons,
				'demons' => $demons,
				'diamonds' => $diamonds,
				'icon_id' => $icon_id,
				'icon_type' => $icon_type,
				'coins' => $coins,
				'user_coins' => $user_coins,
				'cube_id' => $cube_id,
				'ship_id' => $ship_id,
				'ball_id' => $ball_id,
				'bird_id' => $bird_id,
				'dart_id' => $dart_id,
				'robot_id' => $robot_id,
				'glow_id' => $glow_id,
				'spider_id' => $spider_id,
				'explosion_id' => $explosion_id,
				'swing_id' => $swing_id,
				'jetpack_id' => $jetpack_id,
				'color1' => $color1,
				'color2' => $color2,
				'color3' => $color3,
				'special' => $special
			]);
	}
}