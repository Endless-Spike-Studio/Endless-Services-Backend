<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerStatistic extends Model
{
	protected $table = 'endless_server.player_statistics';

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}