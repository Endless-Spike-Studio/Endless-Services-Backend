<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MapPack extends Model
{
	protected $table = 'endless_server.map_packs';

	public function levels(): HasMany
	{
		return $this->hasMany(MapPackLevel::class);
	}
}