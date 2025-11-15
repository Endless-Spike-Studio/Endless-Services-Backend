<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\MapPack;
use App\GeometryDash\Enums\Objects\GeometryDashMapPackObjectDefinition;
use App\GeometryDash\Objects\GameObject;

readonly class GameMapPackObject extends GameObject
{
	public function __construct(
		protected MapPack $model
	)
	{
		parent::__construct(GeometryDashMapPackObjectDefinition::class, GeometryDashMapPackObjectDefinition::GLUE);
	}

	protected function properties(): array
	{
		return [
			GeometryDashMapPackObjectDefinition::ID->value => function () {
				return $this->model->id;
			},
			GeometryDashMapPackObjectDefinition::NAME->value => function () {
				return $this->model->name;
			},
			GeometryDashMapPackObjectDefinition::LEVELS->value => function () {
				return $this->model->levels()
					->orderByDesc('index')
					->get()
					->pluck('level_id')
					->join(',');
			},
			GeometryDashMapPackObjectDefinition::STARS->value => function () {
				return $this->model->stars;
			},
			GeometryDashMapPackObjectDefinition::COINS->value => function () {
				return $this->model->coins;
			},
			GeometryDashMapPackObjectDefinition::DIFFICULTY->value => function () {
				return $this->model->difficulty;
			},
			GeometryDashMapPackObjectDefinition::TEXT_COLOR->value => function () {
				return $this->model->text_color;
			},
			GeometryDashMapPackObjectDefinition::BAR_COLOR->value => function () {
				return $this->model->bar_color;
			}
		];
	}
}