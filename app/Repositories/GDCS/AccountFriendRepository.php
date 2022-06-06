<?php

namespace App\Repositories\GDCS;

use App\Models\GDCS\AccountFriend;

class AccountFriendRepository
{
    public function __construct(
        protected AccountFriend $model
    )
    {
    }

    public function findNewByAccount(int $accountID): AccountFriend
    {
        return $this->model
            ->where([
                'account_id' => $accountID,
                'new' => true
            ])
            ->union(
                AccountFriend::query()
                    ->where([
                        'friend_account_id' => $accountID,
                        'friend_new' => true
                    ])->toBase()
            );
    }
}
