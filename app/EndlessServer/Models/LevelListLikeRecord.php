<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelListLikeRecord extends Model
{
	protected $table = 'endless_server.level_list_like_records';

	protected $fillable = ['player_id'];

	public function levelList(): BelongsTo
	{
		return $this->belongsTo(LevelList::class);
	}

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}