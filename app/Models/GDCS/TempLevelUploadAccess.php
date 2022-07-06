<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempLevelUploadAccess extends Model
{
    protected $table = 'gdcs_temp_level_upload_accesses';

    protected $fillable = ['ip'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
