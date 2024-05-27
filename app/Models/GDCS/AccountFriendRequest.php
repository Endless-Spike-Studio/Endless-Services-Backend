<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountFriendRequest extends Model
{
	protected $table = 'gdcs_account_friend_requests';
	protected $fillable = ['account_id', 'target_account_id', 'comment', 'new'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'account_id');
	}

	public function target_account(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'target_account_id');
	}
}
