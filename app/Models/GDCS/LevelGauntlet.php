<?php

namespace App\Models\GDCS;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LevelGauntlet extends Model
{
    protected $table = 'gdcs_level_gauntlets';

    public function getLevelsAttribute(): array
    {
        return [$this->level1_id, $this->level2_id, $this->level3_id, $this->level4_id, $this->level5_id];
    }
}
