<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;

class LevelGauntlet extends Model
{
	protected $table = 'gdcs_level_gauntlets';

	public function getLevelsAttribute(): array
	{
		return [$this->level1_id, $this->level2_id, $this->level3_id, $this->level4_id, $this->level5_id];
	}
}
