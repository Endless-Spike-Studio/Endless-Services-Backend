<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountFriendRequest extends Model
{
	protected $table = 'endless_server.account_friend_requests';

	protected $fillable = ['target_account_id', 'comment', 'new'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function targetAccount(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'target_account_id');
	}
}