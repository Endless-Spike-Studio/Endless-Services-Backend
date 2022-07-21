<?php

namespace App\Repositories\GDCS;

use App\Models\GDCS\AccountLink;
use Illuminate\Database\Eloquent\Builder;

class AccountLinkRepository
{
    public function __construct(
        protected AccountLink $model
    )
    {
    }

    public function findByAccount(int $accountID, int $linkID): AccountLink|Builder
    {
        return $this->model
            ->query()
            ->where([
                'id' => $linkID,
                'account_id' => $accountID,
            ]);
    }
}
