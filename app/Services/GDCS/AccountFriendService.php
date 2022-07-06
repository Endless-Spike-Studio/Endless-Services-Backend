<?php

namespace App\Services\GDCS;

use App\Models\GDCS\AccountFriend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AccountFriendService
{
    public function create(int $accountID, int $targetAccountID): Model|AccountFriend
    {
        return AccountFriend::query()
            ->create([
                'account_id' => $accountID,
                'friend_account_id' => $targetAccountID,
            ]);
    }

    public function check(int $accountID, int $targetAccountID): bool
    {
        return AccountFriend::query()
            ->where('account_id', $accountID)
            ->where('friend_account_id', $targetAccountID)
            ->orWhere(function (Builder $query) use ($accountID, $targetAccountID) {
                $query->where('account_id', $targetAccountID)
                    ->where('friend_account_id', $accountID);
            })
            ->exists();
    }
}
