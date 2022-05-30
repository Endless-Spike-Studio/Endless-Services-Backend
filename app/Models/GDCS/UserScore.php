<?php

namespace App\Models\GDCS;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class UserScore extends Model
{
    protected $table = 'gdcs_user_scores';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
