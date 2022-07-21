<?php

namespace App\Repositories\GDCS;

use App\Models\GDCS\AccountFriendRequest;
use Illuminate\Database\Eloquent\Builder;

class AccountFriendRequestRepository
{
    public function __construct(
        protected AccountFriendRequest $model
    )
    {
    }

    public function findNewByAccount(int $accountID): AccountFriendRequest|Builder
    {
        return $this->model
            ->query()
            ->where([
                'target_account_id' => $accountID,
                'new' => true,
            ]);
    }
}
