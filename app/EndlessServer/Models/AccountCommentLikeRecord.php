<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountCommentLikeRecord extends Model
{
	protected $table = 'endless_server.account_comment_like_records';

	protected $fillable = ['player_id'];

	public function accountComment(): BelongsTo
	{
		return $this->belongsTo(AccountComment::class);
	}

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}