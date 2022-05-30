<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\LevelPackDifficulty;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LevelPack extends Model
{
    protected $table = 'gdcs_level_packs';

    protected $casts = [
        'difficulty' => LevelPackDifficulty::class
    ];
}
