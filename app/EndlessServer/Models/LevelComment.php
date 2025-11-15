<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelComment extends Model
{
	protected $table = 'endless_server.level_comments';

	protected $fillable = ['account_id', 'level_id', 'content', 'spam', 'percent'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}

	public function likeRecords(): HasMany
	{
		return $this->hasMany(LevelCommentLikeRecord::class);
	}
}