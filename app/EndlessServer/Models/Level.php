<?php

namespace App\EndlessServer\Models;

use App\GeometryDash\Enums\GeometryDashLevelCoinCounts;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelRatingDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelRatingEpicTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Level extends Model
{
	protected $table = 'endless_server.levels';

	protected $fillable = ['player_id', 'game_version', 'name', 'description', 'version', 'length', 'audio_track_id', 'password', 'original_level_id', 'two_player_mode_enabled', 'objects', 'coins', 'requested_stars', 'unlisted_type', 'ldm_enabled', 'editor_time', 'previous_editor_time', 'extra', 'replay', 'verification_time'];

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}

	public function rating(): HasOne
	{
		return $this->hasOne(LevelRating::class)->withDefault([
			'difficulty' => GeometryDashLevelRatingDifficulties::NA->value,
			'stars' => 0,
			'coin_verified' => false,
			'featured_score' => 0,
			'epic_type' => GeometryDashLevelRatingEpicTypes::NONE->value,
			'demon_difficulty' => null
		]);
	}

	public function originalLevel(): HasOne
	{
		return $this->hasOne(Level::class, 'original_level_id');
	}

	public function songMappings(): HasMany
	{
		return $this->hasMany(LevelSongMapping::class);
	}

	public function soundEffectMappings(): HasMany
	{
		return $this->hasMany(LevelSoundEffectMapping::class);
	}

	public function downloadRecords(): HasMany
	{
		return $this->hasMany(LevelDownloadRecord::class);
	}

	public function reports(): HasMany
	{
		return $this->hasMany(LevelReport::class);
	}

	public function comments(): HasMany
	{
		return $this->hasMany(LevelComment::class);
	}

	public function mapPacks(): BelongsToMany
	{
		return $this->belongsToMany(MapPack::class, MapPackLevel::class);
	}

	public function levelLists(): BelongsToMany
	{
		return $this->belongsToMany(LevelList::class, LevelListLevel::class);
	}

	public function likeRecords(): Level
	{
		return $this->hasMany(LevelLikeRecord::class);
	}

	protected function casts(): array
	{
		return [
			'length' => GeometryDashLevelLengths::class,
			'two_player_mode_enabled' => 'boolean',
			'coins' => GeometryDashLevelCoinCounts::class,
			'ldm_enabled' => 'boolean'
		];
	}
}