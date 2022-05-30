<?php

namespace App\Services\GDCS;

use App\Models\GDCS\AccountBlock;
use App\Repositories\GDCS\AccountMessageRepository;

class AccountBlockService
{
    public function create(int $accountID, int $targetAccountID): bool
    {
        $block = AccountBlock::query()
            ->firstOrCreate([
                'account_id' => $accountID,
                'target_account_id' => $targetAccountID,
            ]);

        return $this->removeMessagesBetween($accountID, $targetAccountID) && $block->wasRecentlyCreated;
    }

    public function check(int $accountID, int $targetAccountID): bool
    {
        return AccountBlock::query()
            ->where('account_id', $accountID)
            ->where('target_account_id', $targetAccountID)
            ->exists();
    }

    protected function removeMessagesBetween(int $accountID, int $targetAccountID): bool
    {
        return app(AccountMessageRepository::class)
            ->whereBetween($accountID, $targetAccountID)
            ->delete();
    }

    public function delete(int $accountID, int $targetAccountID): bool
    {
        return AccountBlock::query()
            ->where('account_id', $accountID)
            ->where('target_account_id', $targetAccountID)
            ->delete();
    }
}
