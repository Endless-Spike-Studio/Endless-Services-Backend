<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelScore extends Model
{
    protected $table = 'gdcs_level_scores';

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
