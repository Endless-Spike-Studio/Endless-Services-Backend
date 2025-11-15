<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelDownloadRecord extends Model
{
	protected $table = 'endless_server.level_download_records';

	protected $fillable = ['player_id'];

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}