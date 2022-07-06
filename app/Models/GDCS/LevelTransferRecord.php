<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\LevelTransferType;
use Illuminate\Database\Eloquent\Model;

class LevelTransferRecord extends Model
{
    protected $table = 'gdcs_level_transfer_records';

    protected $fillable = ['type', 'original_level_id', 'level_id'];

    protected $casts = [
        'type' => LevelTransferType::class,
    ];
}
