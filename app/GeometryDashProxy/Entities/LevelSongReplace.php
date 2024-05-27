<?php

namespace App\GeometryDashProxy\Entities;

use App\Models\NGProxy\Song;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelSongReplace extends Model
{
	protected $table = 'gdproxy_level_song_replaces';

	public function song(): BelongsTo
	{
		return $this->belongsTo(Song::class, 'song_id', 'song_id');
	}
}