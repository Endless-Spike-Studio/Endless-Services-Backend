<?php

namespace App\Repositories\GDCS;

use App\Models\GDCS\AccountFriend;
use Illuminate\Database\Eloquent\Builder;

class AccountFriendRepository
{
    public function __construct(
        protected AccountFriend $model
    )
    {
    }

    public function findNewByAccount(int $accountID): AccountFriend|Builder
    {
        return $this->model
            ->query()
            ->where([
                'account_id' => $accountID,
                'new' => true,
            ])
            ->union(
                AccountFriend::query()
                    ->where([
                        'friend_account_id' => $accountID,
                        'friend_new' => true,
                    ])->toBase()
            );
    }
}
