<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LevelList extends Model
{
	protected $table = 'endless_server.level_lists';

	protected $fillable = ['account_id', 'name', 'description', 'difficulty', 'version', 'original_level_list_id', 'unlisted_type'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function levels(): HasMany
	{
		return $this->hasMany(LevelListLevel::class);
	}

	public function rating(): HasOne
	{
		return $this->hasOne(LevelListRating::class);
	}

	public function downloadRecords(): HasMany
	{
		return $this->hasMany(LevelListDownloadRecord::class);
	}

	public function likeRecords(): HasMany
	{
		return $this->hasMany(LevelListLikeRecord::class);
	}
}