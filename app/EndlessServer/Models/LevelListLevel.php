<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelListLevel extends Model
{
	protected $table = 'endless_server.level_list_levels';

	protected $fillable = ['level_id', 'index'];

	public function levelList(): BelongsTo
	{
		return $this->belongsTo(LevelList::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}