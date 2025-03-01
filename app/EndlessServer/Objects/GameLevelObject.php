<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\Level;
use App\EndlessServer\Services\GameLevelDataStorageService;
use App\GeometryDash\Enums\GeometryDashLevelRatingDifficulties;
use App\GeometryDash\Enums\Objects\GeometryDashLevelObjectDefinitions;
use App\GeometryDash\Objects\GameObject;

readonly class GameLevelObject extends GameObject
{
	public function __construct(
		protected Level $model
	)
	{
		parent::__construct(GeometryDashLevelObjectDefinitions::class, GeometryDashLevelObjectDefinitions::GLUE);
	}

	protected function properties(): array
	{
		return [
			GeometryDashLevelObjectDefinitions::ID->value => function () {
				return $this->model->id;
			},
			GeometryDashLevelObjectDefinitions::NAME->value => function () {
				return $this->model->name;
			},
			GeometryDashLevelObjectDefinitions::DESCRIPTION->value => function () {
				return $this->model->description;
			},
			GeometryDashLevelObjectDefinitions::DATA->value => function () {
				$storage = app(GameLevelDataStorageService::class);

				$storage->level = $this->model;

				return $storage->fetch();
			},
			GeometryDashLevelObjectDefinitions::VERSION->value => function () {
				return $this->model->version;
			},
			GeometryDashLevelObjectDefinitions::CREATOR_PLAYER_ID->value => function () {
				return $this->model->player->id;
			},
			GeometryDashLevelObjectDefinitions::IS_RATED->value => function () {
				if ($this->model->rating->difficulty === GeometryDashLevelRatingDifficulties::NA->value) {
					return 0;
				}

				return 10;
			},
			GeometryDashLevelObjectDefinitions::DIFFICULTY->value => function () {
				return $this->model->rating->difficulty;
			},
			GeometryDashLevelObjectDefinitions::DOWNLOADS->value => function () {
				return 0; // TODO
			},
			GeometryDashLevelObjectDefinitions::AUDIO_TRACK->value => function () {
				return $this->model->audio_track_id;
			},
			GeometryDashLevelObjectDefinitions::GAME_VERSION->value => function () {
				return $this->model->game_version;
			},
			GeometryDashLevelObjectDefinitions::LIKES->value => function () {
				return 0; // TODO
			},
			GeometryDashLevelObjectDefinitions::LENGTH->value => function () {
				return $this->model->length->value;
			},
			GeometryDashLevelObjectDefinitions::IS_DEMON->value => function () {
				if ($this->model->rating->difficulty !== GeometryDashLevelRatingDifficulties::AUTO_OR_DEMON->value) {
					return false;
				}

				return $this->model->rating->demon_difficulty !== null;
			},
			GeometryDashLevelObjectDefinitions::STARS->value => function () {
				return $this->model->rating->stars;
			},
			GeometryDashLevelObjectDefinitions::FEATURED_SCORE->value => function () {
				return $this->model->rating->featured_score;
			},
			GeometryDashLevelObjectDefinitions::IS_AUTO->value => function () {
				if ($this->model->rating->difficulty !== GeometryDashLevelRatingDifficulties::AUTO_OR_DEMON->value) {
					return false;
				}

				return $this->model->rating->demon_difficulty === null;
			},
			GeometryDashLevelObjectDefinitions::PASSWORD->value => function () {
				return $this->model->password;
			},
			GeometryDashLevelObjectDefinitions::CREATED_AT->value => function () {
				return $this->model->created_at->diffForHumans(syntax: true);
			},
			GeometryDashLevelObjectDefinitions::UPDATED_AT->value => function () {
				return $this->model->updated_at->diffForHumans(syntax: true);
			},
			GeometryDashLevelObjectDefinitions::ORIGINAL_LEVEL_ID->value => function () {
				return $this->model->original_level_id;
			},
			GeometryDashLevelObjectDefinitions::IS_TWO_PLAYER->value => function () {
				return $this->model->two_player_mode_enabled;
			},
			GeometryDashLevelObjectDefinitions::SONG_ID->value => function () {
				$this->model->loadCount('songMappings');

				if ($this->model->song_mappings_count !== 1) {
					return null;
				}

				return $this->model->songMappings[0]->newgrounds_song_id;
			},
			GeometryDashLevelObjectDefinitions::COINS->value => function () {
				return $this->model->coins->value;
			},
			GeometryDashLevelObjectDefinitions::IS_COIN_VERIFIED->value => function () {
				return $this->model->rating->coin_verified;
			},
			GeometryDashLevelObjectDefinitions::REQUESTED_STARS->value => function () {
				return $this->model->requested_stars;
			},
			GeometryDashLevelObjectDefinitions::IS_LDM->value => function () {
				return $this->model->ldm_enabled;
			},
			GeometryDashLevelObjectDefinitions::SPECIAL_ID->value => function () {
				return null; // TODO
			},
			GeometryDashLevelObjectDefinitions::EPIC_TYPE->value => function () {
				return $this->model->rating->epic_type;
			},
			GeometryDashLevelObjectDefinitions::DEMON_DIFFICULTY->value => function () {
				return $this->model->rating->demon_difficulty;
			},
			GeometryDashLevelObjectDefinitions::GAUNTLET_ID->value => function () {
				return null; // TODO
			},
			GeometryDashLevelObjectDefinitions::OBJECTS->value => function () {
				return $this->model->objects;
			},
			GeometryDashLevelObjectDefinitions::EDITOR_TIME->value => function () {
				return $this->model->editor_time;
			},
			GeometryDashLevelObjectDefinitions::PREVIOUS_EDITOR_TIME->value => function () {
				return $this->model->previous_editor_time;
			},
			GeometryDashLevelObjectDefinitions::SONG_IDS->value => function () {
				return $this->model->songMappings->pluck('newgrounds_song_id')->join(',');
			},
			GeometryDashLevelObjectDefinitions::SOUND_EFFECT_IDS->value => function () {
				$this->model->soundEffectMappings->pluck('sound_effect_id')->join(',');
			},
			GeometryDashLevelObjectDefinitions::VERIFICATION_TIME->value => function () {
				return $this->model->verification_time;
			}
		];
	}
}