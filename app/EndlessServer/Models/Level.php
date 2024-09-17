<?php

namespace App\EndlessServer\Models;

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
}