<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelPlatformerScore extends Model
{
	protected $table = 'endless_server.level_platformer_scores';

	protected $fillable = ['account_id', 'level_id', 'time', 'points', 'attempts', 'clicks', 'attempt_seconds', 'coins', 'special_id'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}