<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
	protected $table = 'endless_server.players';

	protected $fillable = ['name', 'uuid', 'udid'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'uuid');
	}

	public function data(): HasOne
	{
		return $this->hasOne(PlayerData::class);
	}

	public function statistic(): HasOne
	{
		return $this->hasOne(PlayerStatistic::class);
	}

	public function chestRecords(): HasMany
	{
		return $this->hasMany(PlayerChestRecord::class);
	}

	public function demonRecords(): HasMany
	{
		return $this->hasMany(PlayerDemonRecord::class);
	}

	public function quests(): HasMany
	{
		return $this->hasMany(PlayerQuest::class);
	}

	public function levels(): HasMany
	{
		return $this->hasMany(Level::class);
	}
}