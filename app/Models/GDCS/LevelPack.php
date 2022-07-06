<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\LevelPackDifficulty;
use Illuminate\Database\Eloquent\Model;

class LevelPack extends Model
{
    protected $table = 'gdcs_level_packs';

    protected $casts = [
        'difficulty' => LevelPackDifficulty::class,
    ];
}
