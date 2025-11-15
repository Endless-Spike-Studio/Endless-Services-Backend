<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\LevelNormalScore;
use App\EndlessServer\Models\LevelPlatformerScore;
use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\Objects\GeometryDashLeaderboardObjectDefinitions;
use App\GeometryDash\Objects\GameObject;

readonly class GameLeaderboardObject extends GameObject
{
	public function __construct(
		protected Player|LevelNormalScore|LevelPlatformerScore $model,
		protected int                                          $rank = 1
	)
	{
		parent::__construct(GeometryDashLeaderboardObjectDefinitions::class, GeometryDashLeaderboardObjectDefinitions::GLUE);
	}

	protected function properties(): array
	{
		return [
			GeometryDashLeaderboardObjectDefinitions::PLAYER_NAME->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->name;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ID->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->id;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_STARS_OR_PERCENT->value => function () {
				if ($this->model instanceof Player) {
					return $this->model->data->stars;
				}

				if ($this->model instanceof LevelNormalScore) {
					return $this->model->percent;
				}

				if ($this->model instanceof LevelPlatformerScore) {
					return $this->model->time;
				}

				return null;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_DEMONS->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->demons;
			},
			GeometryDashLeaderboardObjectDefinitions::RANKING->value => function () {
				return $this->rank;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_CREATOR_POINTS->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->statistic->creator_points;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ICON_ID->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->icon_id;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_COLOR_1->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->color1;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_COLOR_2->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->color2;
			},
			GeometryDashLeaderboardObjectDefinitions::COINS->value => function () {
				if ($this->model instanceof Player) {
					return $this->model->account->player->data->coins;
				}

				return $this->model->coins;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ICON_TYPE->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->icon_type;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_SPECIAL->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->special;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_UUID->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->uuid;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_USER_COINS->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->user_coins;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_DIAMONDS->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->diamonds;
			},
			GeometryDashLeaderboardObjectDefinitions::AGE->value => function () {
				return $this->model->updated_at->diffForHumans(syntax: true);
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_MOONS->value => function () {
				$player = $this->model->account->player;

				if ($this->model instanceof Player) {
					$player = $this->model;
				}

				return $player->data->moons;
			}
		];
	}
}