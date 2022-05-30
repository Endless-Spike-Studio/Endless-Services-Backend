<?php

namespace App\Models\GDCS;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DailyLevel extends Model
{
    protected $table = 'gdcs_daily_levels';
    protected $fillable = ['level_id', 'apply_at'];
    protected $dates = ['apply_at'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}
