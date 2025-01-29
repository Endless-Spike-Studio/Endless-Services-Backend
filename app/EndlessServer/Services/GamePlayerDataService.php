<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Models\PlayerData;

readonly class GamePlayerDataService
{
	public function initialize(int $playerId): void
	{
		$exists = PlayerData::query()
			->where('player_id', $playerId)
			->exists();

		if ($exists) {
			return;
		}

		PlayerData::query()
			->create([
				'player_id' => $playerId,
				'stars' => 0,
				'moons' => 0,
				'demons' => 0,
				'diamonds' => 0,
				'icon_id' => 1,
				'icon_type' => 0,
				'coins' => 0,
				'user_coins' => 0,
				'color1' => 0,
				'color2' => 3,
				'color3' => -1,
				'cube_id' => 1,
				'ship_id' => 1,
				'ball_id' => 1,
				'bird_id' => 1,
				'dart_id' => 1,
				'robot_id' => 1,
				'glow_id' => 0,
				'spider_id' => 1,
				'explosion_id' => 1,
				'swing_id' => 1,
				'jetpack_id' => 1,
				'special' => 1
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