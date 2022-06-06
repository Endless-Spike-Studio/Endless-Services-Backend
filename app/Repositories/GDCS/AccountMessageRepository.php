<?php

namespace App\Repositories\GDCS;

use App\Models\GDCS\AccountMessage;
use Illuminate\Database\Eloquent\Builder;

class AccountMessageRepository
{
    public function __construct(
        protected AccountMessage $model
    )
    {
    }

    public function findNewByAccount(int $accountID): AccountMessage
    {
        return $this->model
            ->where([
                'target_account_id' => $accountID,
                'new' => true
            ]);
    }

    public function whereBetween(int $accountID, int $targetAccountID): Builder|AccountMessage
    {
        return $this->model
            ->where([
                'account_id' => $accountID,
                'target_account_id' => $targetAccountID
            ])
            ->orWhere(function (Builder $query) use ($accountID, $targetAccountID) {
                $query->where([
                    'target_account_id' => $accountID,
                    'account_id' => $targetAccountID
                ]);
            });
    }
}
