<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerQuest extends Model
{
	protected $table = 'endless_server.player_quests';

	protected $fillable = ['player_id', 'name', 'collect_type', 'collect_count', 'reward_count'];

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}