<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelListRating extends Model
{
	protected $table = 'endless_server.level_list_ratings';

	public function levelList(): BelongsTo
	{
		return $this->belongsTo(LevelList::class);
	}
}