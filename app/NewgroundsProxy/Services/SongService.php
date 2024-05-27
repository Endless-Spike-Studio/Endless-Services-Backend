<?php

namespace App\NewgroundsProxy\Services;

use App\NewgroundsProxy\Entities\Song;

class SongService
{
	public function get(int $songId): Song
	{
		return Song::findOrNew($songId);
	}
}