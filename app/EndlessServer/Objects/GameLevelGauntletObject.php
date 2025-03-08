<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\LevelGauntlet;
use App\GeometryDash\Enums\Objects\GeometryDashLevelGauntletObjectDefinition;
use App\GeometryDash\Objects\GameObject;

readonly class GameLevelGauntletObject extends GameObject
{
	public function __construct(
		protected LevelGauntlet $model
	)
	{
		parent::__construct(GeometryDashLevelGauntletObjectDefinition::class, GeometryDashLevelGauntletObjectDefinition::GLUE);
	}

	protected function properties(): array
	{
		return [
			GeometryDashLevelGauntletObjectDefinition::ID->value => function () {
				return $this->model->gauntlet_id;
			},
			GeometryDashLevelGauntletObjectDefinition::LEVELS->value => function () {
				return implode(',', [
					$this->model->level1_id,
					$this->model->level2_id,
					$this->model->level3_id,
					$this->model->level4_id,
					$this->model->level5_id
				]);
			}
		];
	}
}