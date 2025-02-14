<?php

namespace App\EndlessServer\Repositories;

use App\EndlessServer\Models\AccountFriend;
use Illuminate\Contracts\Database\Query\Builder;

class AccountFriendRepository
{
	public function queryIdsByAccountId(int $accountId)
	{
		return $this->createQueryByAccountId($accountId)
			->get()
			->map(function (AccountFriend $friend) use ($accountId) {
				if ($friend->target_account_id == $accountId) {
					return $friend->account_id;
				}

				return $friend->target_account_id;
			});
	}

	public function createQueryByAccountId(int $accountId)
	{
		return AccountFriend::query()
			->where('account_id', $accountId)
			->orWhere('target_account_id', $accountId);
	}

	public function createQueryByAccountIdAndTargetAccountId(int $accountId, int $targetAccountId)
	{
		return AccountFriend::query()
			->where(function (Builder $query) use ($targetAccountId, $accountId) {
				$query->where('account_id', $accountId);
				$query->where('target_account_id', $targetAccountId);
			})
			->orWhere(function (Builder $query) use ($targetAccountId, $accountId) {
				$query->where('target_account_id', $accountId);
				$query->where('account_id', $targetAccountId);
			});
	}
}