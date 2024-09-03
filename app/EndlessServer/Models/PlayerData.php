<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerData extends Model
{
	protected $table = 'endless_server.player_data';

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}