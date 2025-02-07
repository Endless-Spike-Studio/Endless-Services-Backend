<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerChestRecord extends Model
{
	protected $table = 'endless_server.player_chest_records';

	protected $fillable = ['player_id', 'type', 'orbs', 'diamonds', 'shards', 'keys'];

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}