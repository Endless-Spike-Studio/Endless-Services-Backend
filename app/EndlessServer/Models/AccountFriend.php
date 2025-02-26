<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountFriend extends Model
{
	protected $table = 'endless_server.account_friends';

	protected $fillable = ['account_id', 'target_account_id', 'comment', 'alias', 'new'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function targetAccount(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'target_account_id');
	}
}