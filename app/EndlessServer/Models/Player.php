<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
	protected $table = 'endless_server.players';

	protected $fillable = ['name', 'uuid', 'udid'];

	public function data(): HasOne
	{
		return $this->hasOne(PlayerData::class);
	}

	public function statistic(): HasOne
	{
		return $this->hasOne(PlayerStatistic::class);
	}
}