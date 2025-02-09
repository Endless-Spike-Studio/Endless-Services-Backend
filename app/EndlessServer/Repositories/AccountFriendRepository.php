<?php

namespace App\EndlessServer\Repositories;

use App\EndlessServer\Models\AccountFriend;

class AccountFriendRepository
{
	public function queryIdsByAccountId(int $accountId)
	{
		return AccountFriend::query()
			->where('account_id', $accountId)
			->orWhere('target_account_id', $accountId)
			->get()
			->map(function (AccountFriend $friend) use ($accountId) {
				if ($friend->target_account_id == $accountId) {
					return $friend->account_id;
				}

				return $friend->target_account_id;
			});
	}
}