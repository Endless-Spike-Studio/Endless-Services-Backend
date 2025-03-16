<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelCommentLikeRecord extends Model
{
	protected $table = 'endless_server.level_comment_like_records';

	public function levelComment(): BelongsTo
	{
		return $this->belongsTo(LevelComment::class);
	}

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}