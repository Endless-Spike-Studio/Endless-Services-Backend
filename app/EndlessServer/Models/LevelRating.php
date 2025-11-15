<?php

namespace App\EndlessServer\Models;

use App\GeometryDash\Enums\GeometryDashLevelRatingDemonDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelRatingDifficulties;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelRating extends Model
{
	protected $table = 'endless_server.level_ratings';

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}

	protected function casts(): array
	{
		return [
			'difficulty' => GeometryDashLevelRatingDifficulties::class,
			'demon_difficulty' => GeometryDashLevelRatingDemonDifficulties::class
		];
	}
}