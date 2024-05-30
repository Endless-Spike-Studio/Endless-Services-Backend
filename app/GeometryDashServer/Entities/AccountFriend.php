<?php

namespace App\GeometryDashServer\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountFriend extends Model
{
	protected $table = 'gdcs_account_friends';

	protected $fillable = ['account_id', 'friend_account_id', 'new', 'friend_new'];

	protected $casts = [
		'new' => 'boolean',
		'friend_new' => 'boolean',
	];

	public static function findBetween(int $accountID, int $targetAccountID): AccountFriend|Builder
	{
		return static::query()
			->where('account_id', $accountID)
			->where('friend_account_id', $targetAccountID)
			->union(function () use ($accountID, $targetAccountID) {
				return static::query()
					->where('account_id', $targetAccountID)
					->where('friend_account_id', $accountID);
			});
	}

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'account_id');
	}

	public function friend_account(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'friend_account_id');
	}
}
