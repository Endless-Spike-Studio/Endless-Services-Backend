<?php

namespace App\EndlessServer\Models;

use App\GeometryDash\Enums\GeometryDashLevelCoinCounts;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelRatingStars;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Level extends Model
{
	protected $table = 'endless_server.levels';

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