<?php

namespace App\Models\GDCS;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class LevelScore extends Model
{
    protected $table = 'gdcs_level_scores';

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
