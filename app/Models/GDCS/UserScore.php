<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserScore extends Model
{
    protected $table = 'gdcs_user_scores';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
