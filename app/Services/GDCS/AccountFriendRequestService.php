<?php

namespace App\Services\GDCS;

use App\Models\GDCS\AccountFriendRequest;
use Illuminate\Database\Eloquent\Model;

class AccountFriendRequestService
{
    public function create(int $accountID, int $targetAccountID, string $comment = null): Model|AccountFriendRequest
    {
        return AccountFriendRequest::query()
            ->create([
                'account_id' => $accountID,
                'target_account_id' => $targetAccountID,
                'comment' => $comment,
            ]);
    }
}
