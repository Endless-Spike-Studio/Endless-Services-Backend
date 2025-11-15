<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelReport extends Model
{
	protected $table = 'endless_server.level_reports';

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}