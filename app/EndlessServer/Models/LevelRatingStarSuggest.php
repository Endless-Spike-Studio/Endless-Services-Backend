<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelRatingStarSuggest extends Model
{
	protected $table = 'endless_server.level_rating_star_suggests';

	protected $fillable = ['player_id', 'level_id', 'stars', 'apply_at'];

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}