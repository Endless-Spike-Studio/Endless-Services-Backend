<?php

namespace App\Repositories\GDCS;

use App\Models\GDCS\AccountLink;
use Illuminate\Database\Eloquent\Builder;

class AccountLinkRepository
{
    public function findByAccount(int $accountID, int $linkID): AccountLink|Builder
    {
        return AccountLink::query()
            ->where('account_id', $accountID)
            ->whereKey($linkID);
    }
}
