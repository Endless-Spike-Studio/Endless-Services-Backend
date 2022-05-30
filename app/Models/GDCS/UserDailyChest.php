<?php

namespace App\Models\GDCS;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class UserDailyChest extends Model
{
    protected $table = 'gdcs_user_daily_chest';

    protected $casts = [
        'small_time' => 'datetime',
        'big_time' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
