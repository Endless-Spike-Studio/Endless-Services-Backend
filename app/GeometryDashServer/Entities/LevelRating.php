<?php

namespace App\GeometryDashServer\Entities;

use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\LevelRatingDifficulty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelRating extends Model
{
	protected $table = 'gdcs_level_ratings';

	protected $fillable = ['level_id', 'stars', 'difficulty', 'featured_score', 'epic', 'coin_verified', 'demon_difficulty', 'auto', 'demon'];

	protected $casts = [
		'difficulty' => LevelRatingDifficulty::class,
		'featured' => 'boolean',
		'epic' => 'boolean',
		'coin_verified' => 'boolean',
		'demon_difficulty' => LevelRatingDemonDifficulty::class,
		'auto' => 'boolean',
		'demon' => 'boolean',
	];

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}
