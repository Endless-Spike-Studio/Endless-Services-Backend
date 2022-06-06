<?php

namespace App\Repositories\GDCS;

use App\Models\GDCS\AccountFriendRequest;

class AccountFriendRequestRepository
{
    public function __construct(
        protected AccountFriendRequest $model
    )
    {
    }

    public function findNewByAccount(int $accountID): AccountFriendRequest
    {
        return $this->model
            ->where([
                'target_account_id' => $accountID,
                'new' => true
            ]);
    }
}
