<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelEvent extends Model
{
	protected $table = 'endless_server.level_events';

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}

	protected function casts(): array
	{
		return [
			'expired_at' => 'datetime'
		];
	}
}