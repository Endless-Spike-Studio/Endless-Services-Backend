<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountComment extends Model
{
    protected $table = 'gdcs_account_comments';

    protected $fillable = ['comment', 'likes'];

    protected $casts = [
        'spam' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
