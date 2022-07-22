<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMessage extends Model
{
    protected $table = 'gdcs_account_messages';

    public static function findBetween(int $accountID, int $targetAccountID): Builder|AccountMessage
    {
        return self::query()
            ->where('account_id', $accountID)
            ->where('target_account_id', $targetAccountID)
            ->orWhere('account_id', $targetAccountID)
            ->where('target_account_id', $accountID);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function target_account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
