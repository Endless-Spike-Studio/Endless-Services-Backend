<?php

namespace App\EndlessServer\Responses;

use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\Objects\GeometryDashLeaderboardObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Carbon;

readonly class GameLeaderboardObjectResponse implements Responsable
{
	public function __construct(
		protected Player $model,
		protected int    $rank = 1
	)
	{
		Carbon::setLocale('en');
	}

	public function toResponse($request)
	{
		return app(GeometryDashObjectService::class)->merge([
			GeometryDashLeaderboardObjectDefinitions::PLAYER_NAME->value => $this->model->name,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ID->value => $this->model->id,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_STARS->value => $this->model->data->stars,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_DEMONS->value => $this->model->data->demons,
			GeometryDashLeaderboardObjectDefinitions::RANKING->value => $this->rank,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_CREATOR_POINTS->value => $this->model->statistic->creator_points,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ICON_ID->value => $this->model->data->icon_id,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_COLOR_1->value => $this->model->data->color1,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_COLOR_2->value => $this->model->data->color2,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_COINS->value => $this->model->data->coins,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_ICON_TYPE->value => $this->model->data->icon_type,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_SPECIAL->value => $this->model->data->special,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_UUID->value => $this->model->uuid,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_USER_COINS->value => $this->model->data->user_coins,
			GeometryDashLeaderboardObjectDefinitions::PLAYER_DIAMONDS->value => $this->model->data->diamonds,
			GeometryDashLeaderboardObjectDefinitions::AGE->value => $this->model->data->updated_at->diffForHumans(syntax: true),
			GeometryDashLeaderboardObjectDefinitions::PLAYER_DIAMONDS->value => $this->model->data->moons
		], GeometryDashLeaderboardObjectDefinitions::GLUE);
	}
}