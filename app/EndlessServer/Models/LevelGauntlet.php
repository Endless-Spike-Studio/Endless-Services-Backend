<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelGauntlet extends Model
{
	protected $table = 'endless_server.level_gauntlets';

	public function level1(): BelongsTo
	{
		return $this->belongsTo(Level::class, 'level1_id');
	}

	public function level2(): BelongsTo
	{
		return $this->belongsTo(Level::class, 'level2_id');
	}

	public function level3(): BelongsTo
	{
		return $this->belongsTo(Level::class, 'level3_id');
	}

	public function level4(): BelongsTo
	{
		return $this->belongsTo(Level::class, 'level4_id');
	}

	public function level5(): BelongsTo
	{
		return $this->belongsTo(Level::class, 'level5_id');
	}
}