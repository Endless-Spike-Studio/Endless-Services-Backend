<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
	protected $table = 'endless_server.players';

	protected $fillable = ['name', 'uuid', 'udid'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function data(): HasOne
	{
		return $this->hasOne(PlayerData::class)
			->withDefault([
				'game_version' => 0,
				'binary_version' => 0,
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

	public function statistic(): HasOne
	{
		return $this->hasOne(PlayerStatistic::class)
			->withDefault([
				'creator_points' => 0,
				'completed_dailies_count' => 0,
				'completed_weeklies_count' => 0,
				'completed_classic_auto_count' => 0,
				'completed_classic_easy_count' => 0,
				'completed_classic_normal_count' => 0,
				'completed_classic_hard_count' => 0,
				'completed_classic_harder_count' => 0,
				'completed_classic_insane_count' => 0,
				'completed_platformer_auto_count' => 0,
				'completed_platformer_easy_count' => 0,
				'completed_platformer_normal_count' => 0,
				'completed_platformer_hard_count' => 0,
				'completed_platformer_harder_count' => 0,
				'completed_platformer_insane_count' => 0,
				'completed_classic_easy_demons_count' => 0,
				'completed_classic_medium_demons_count' => 0,
				'completed_classic_hard_demons_count' => 0,
				'completed_classic_insane_demons_count' => 0,
				'completed_classic_extreme_demons_count' => 0,
				'completed_platformer_easy_demons_count' => 0,
				'completed_platformer_medium_demons_count' => 0,
				'completed_platformer_hard_demons_count' => 0,
				'completed_platformer_insane_demons_count' => 0,
				'completed_platformer_extreme_demons_count' => 0,
				'completed_gauntlet_levels_count' => 0,
				'completed_gauntlet_demon_levels_count' => 0
			]);
	}

	public function chestRecords(): HasMany
	{
		return $this->hasMany(PlayerChestRecord::class);
	}

	public function demonRecords(): HasMany
	{
		return $this->hasMany(PlayerDemonRecord::class);
	}

	public function quests(): HasMany
	{
		return $this->hasMany(PlayerQuest::class);
	}

	public function levels(): HasMany
	{
		return $this->hasMany(Level::class);
	}
}