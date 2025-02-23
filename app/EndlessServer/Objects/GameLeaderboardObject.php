<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\Objects\GeometryDashLeaderboardObjectDefinitions;
use App\GeometryDash\Objects\GameObject;

readonly class GameLeaderboardObject extends GameObject
{
	public function __construct(
		protected Player $model,
		protected int    $rank = 1
	)
	{
		parent::__construct(GeometryDashLeaderboardObjectDefinitions::class, GeometryDashLeaderboardObjectDefinitions::GLUE);
	}

	protected function properties(): array
	{
		return [
			GeometryDashLeaderboardObjectDefinitions::PLAYER_NAME->value => function () {
				return $this->model->name;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ID->value => function () {
				return $this->model->id;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_STARS->value => function () {
				return $this->model->data->stars;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_DEMONS->value => function () {
				return $this->model->data->demons;
			},
			GeometryDashLeaderboardObjectDefinitions::RANKING->value => function () {
				return $this->rank;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_CREATOR_POINTS->value => function () {
				return $this->model->statistic->creator_points;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ICON_ID->value => function () {
				return $this->model->data->icon_id;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_COLOR_1->value => function () {
				return $this->model->data->color1;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_COLOR_2->value => function () {
				return $this->model->data->color2;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_COINS->value => function () {
				return $this->model->data->coins;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ICON_TYPE->value => function () {
				return $this->model->data->icon_type;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_SPECIAL->value => function () {
				return $this->model->data->special;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_UUID->value => function () {
				return $this->model->uuid;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_USER_COINS->value => function () {
				return $this->model->data->user_coins;
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_DIAMONDS->value => function () {
				return $this->model->data->diamonds;
			},
			GeometryDashLeaderboardObjectDefinitions::AGE->value => function () {
				return $this->model->data->updated_at->diffForHumans(syntax: true);
			},
			GeometryDashLeaderboardObjectDefinitions::PLAYER_MOONS->value => function () {
				return $this->model->data->moons;
			}
		];
	}
}