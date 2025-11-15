<?php

namespace App\EndlessProxy\Objects;

use App\EndlessProxy\Models\NewgroundsSong;
use App\GeometryDash\Enums\Objects\GeometryDashSongObjectDefinitions;
use App\GeometryDash\Objects\GameObject;

readonly class GameSongObject extends GameObject
{
	public function __construct(
		protected NewgroundsSong $model
	)
	{
		parent::__construct(GeometryDashSongObjectDefinitions::class, GeometryDashSongObjectDefinitions::GLUE);
	}

	protected function properties(): array
	{
		return [
			GeometryDashSongObjectDefinitions::ID->value => function () {
				return $this->model->song_id;
			},
			GeometryDashSongObjectDefinitions::NAME->value => function () {
				return $this->model->name;
			},
			GeometryDashSongObjectDefinitions::ARTIST_ID->value => function () {
				return $this->model->artist_id;
			},
			GeometryDashSongObjectDefinitions::ARTIST_NAME->value => function () {
				return $this->model->artist_name;
			},
			GeometryDashSongObjectDefinitions::SIZE->value => function () {
				return $this->model->size;
			},
			GeometryDashSongObjectDefinitions::DOWNLOAD_URL->value => function () {
				return $this->model->download_url;
			}
		];
	}
}