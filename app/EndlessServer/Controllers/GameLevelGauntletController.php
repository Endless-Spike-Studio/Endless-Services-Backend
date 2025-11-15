<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Models\LevelGauntlet;
use App\EndlessServer\Objects\GameLevelGauntletObject;
use App\EndlessServer\Requests\GameLevelGauntletListRequest;
use App\GeometryDash\Enums\GeometryDashSalts;
use App\GeometryDash\Enums\Objects\GeometryDashLevelGauntletObjectDefinition;

readonly class GameLevelGauntletController
{
	public function list(GameLevelGauntletListRequest $request): string
	{
		$request->validated();

		$gauntlets = LevelGauntlet::query()
			->get();

		return implode(GeometryDashLevelGauntletObjectDefinition::SEGMENTATION, [
			$gauntlets->map(function (LevelGauntlet $gauntlet) {
				return new GameLevelGauntletObject($gauntlet)->merge();
			})->join(GeometryDashLevelGauntletObjectDefinition::SEPARATOR),
			sha1(
				$gauntlets->map(function (LevelGauntlet $gauntlet) {
					return implode('', [
						$gauntlet->gauntlet_id,
						implode(',', [
							$gauntlet->level1_id,
							$gauntlet->level2_id,
							$gauntlet->level3_id,
							$gauntlet->level4_id,
							$gauntlet->level5_id
						])
					]);
				})->join('') .
				GeometryDashSalts::LEVEL->value
			)
		]);
	}
}