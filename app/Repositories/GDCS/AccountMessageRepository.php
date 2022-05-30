<?php

namespace App\Repositories\GDCS;

use App\Models\GDCS\AccountMessage;
use Illuminate\Database\Eloquent\Builder;

class AccountMessageRepository
{
    public function whereBetween(int $accountID, int $targetAccountID): Builder|AccountMessage
    {
        return AccountMessage::query()
            ->where('account_id', $accountID)
            ->where('target_account_id', $targetAccountID)
            ->orWhere(function (Builder $query) use ($accountID, $targetAccountID) {
                $query->where('account_id', $targetAccountID)
                    ->where('target_account_id', $accountID);
            });
    }
}
