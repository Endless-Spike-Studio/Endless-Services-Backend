<?php

namespace App\Models\GDCS;

use App\Casts\Base64Cast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountComment extends Model
{
    protected $table = 'gdcs_account_comments';

    protected $fillable = ['comment'];

    protected $casts = [
        'comment' => Base64Cast::class,
        'spam' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
