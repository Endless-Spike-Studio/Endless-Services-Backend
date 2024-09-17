<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerDemonRecord extends Model
{
	protected $table = 'endless_server.player_demon_records';

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}