<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\LevelList;
use App\GeometryDash\Enums\Objects\GeometryDashLevelListObjectDefinitions;
use App\GeometryDash\Objects\GameObject;
use Base64Url\Base64Url;

readonly class GameLevelListObject extends GameObject
{
	public function __construct(
		protected LevelList $model
	)
	{
		parent::__construct(GeometryDashLevelListObjectDefinitions::class, GeometryDashLevelListObjectDefinitions::GLUE);
	}

	protected function properties(): array
	{
		return [
			GeometryDashLevelListObjectDefinitions::ID->value => function () {
				return $this->model->id;
			},
			GeometryDashLevelListObjectDefinitions::NAME->value => function () {
				return $this->model->name;
			},
			GeometryDashLevelListObjectDefinitions::DESCRIPTION->value => function () {
				$description = null;

				if ($this->model->description !== null) {
					$description = Base64Url::encode($this->model->description, true);
				}

				return $description;
			},
			GeometryDashLevelListObjectDefinitions::VERSION->value => function () {
				return $this->model->version;
			},
			GeometryDashLevelListObjectDefinitions::DIFFICULTY->value => function () {
				return $this->model->difficulty;
			},
			GeometryDashLevelListObjectDefinitions::DOWNLOADS->value => function () {
				return $this->model->downloadRecords()
					->count();
			},
			GeometryDashLevelListObjectDefinitions::LIKES->value => function () {
				// TODO
				return 0;
			},
			GeometryDashLevelListObjectDefinitions::RATED->value => function () {
				return $this->model->rating !== null;
			},
			GeometryDashLevelListObjectDefinitions::UPLOAD_TIMESTAMP->value => function () {
				return $this->model->created_at->timestamp;
			},
			GeometryDashLevelListObjectDefinitions::UPDATE_TIMESTAMP->value => function () {
				return $this->model->updated_at->timestamp;
			},
			GeometryDashLevelListObjectDefinitions::ACCOUNT_ID->value => function () {
				return $this->model->account->id;
			},
			GeometryDashLevelListObjectDefinitions::USERNAME->value => function () {
				return $this->model->account->player->name;
			},
			GeometryDashLevelListObjectDefinitions::LEVEL_IDS->value => function () {
				return $this->model->levels()
					->orderBy('index')
					->pluck('level_id')
					->join(',');
			},
			GeometryDashLevelListObjectDefinitions::REWARD_DIAMONDS->value => function () {
				if ($this->model->rating === null) {
					return null;
				}

				return $this->model->rating->diamonds;
			},
			GeometryDashLevelListObjectDefinitions::REWARD_REQUIRE_LEVEL_COUNT->value => function () {
				if ($this->model->rating === null) {
					return null;
				}

				return $this->model->rating->require_levels;
			}
		];
	}
}