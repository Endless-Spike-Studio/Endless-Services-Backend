<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapPackLevel extends Model
{
	protected $table = 'endless_server.map_pack_levels';

	public function mapPack(): BelongsTo
	{
		return $this->belongsTo(MapPack::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}