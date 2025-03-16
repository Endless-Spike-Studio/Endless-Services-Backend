<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelLikeRecord extends Model
{
	protected $table = 'endless_server.level_like_records';

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}