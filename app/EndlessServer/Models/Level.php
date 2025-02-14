<?php

namespace App\EndlessServer\Models;

use App\GeometryDash\Enums\GeometryDashLevelCoinCounts;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelRatingStars;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Level extends Model
{
	protected $table = 'endless_server.levels';

	protected $fillable = ['player_id', 'name', 'description', 'version', 'length', 'audio_track_id', 'password', 'original_level_id', '2p_mode', 'objects', 'coins', 'requested_stars', 'unlisted_type', 'ldm_mode', 'editor_time', 'previous_editor_time', 'extra', 'replay', 'verification_time'];

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}

	public function rating(): HasOne
	{
		return $this->hasOne(LevelRating::class);
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

	protected function casts(): array
	{
		return [
			'length' => GeometryDashLevelLengths::class,
			'2p_mode' => 'boolean',
			'coins' => GeometryDashLevelCoinCounts::class,
			'requested_stars' => GeometryDashLevelRatingStars::class,
			'ldm_mode' => 'boolean'
		];
	}
}