<?php

namespace App\Models\GDCS;

use App\Casts\Base64Cast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelComment extends Model
{
    protected $table = 'gdcs_level_comments';

    protected $casts = [
        'comment' => Base64Cast::class,
        'spam' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
