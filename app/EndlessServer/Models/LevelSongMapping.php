<?php

namespace App\EndlessServer\Models;

use App\EndlessProxy\Models\NewgroundsSong;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelSongMapping extends Model
{
	protected $table = 'endless_server.level_song_mappings';

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}

	public function newgroundsSong(): BelongsTo
	{
		return $this->belongsTo(NewgroundsSong::class);
	}
}