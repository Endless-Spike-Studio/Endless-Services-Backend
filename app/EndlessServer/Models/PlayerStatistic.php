<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerStatistic extends Model
{
	protected $table = 'endless_server.player_statistics';

	protected $fillable = ['player_id', 'creator_points', 'completed_dailies_count', 'completed_weeklies_count', 'completed_classic_auto_count', 'completed_classic_easy_count', 'completed_classic_normal_count', 'completed_classic_hard_count', 'completed_classic_harder_count', 'completed_classic_insane_count', 'completed_platformer_auto_count', 'completed_platformer_easy_count', 'completed_platformer_normal_count', 'completed_platformer_hard_count', 'completed_platformer_harder_count', 'completed_platformer_insane_count', 'completed_classic_easy_demons_count', 'completed_classic_medium_demons_count', 'completed_classic_hard_demons_count', 'completed_classic_insane_demons_count', 'completed_classic_extreme_demons_count', 'completed_platformer_easy_demons_count', 'completed_platformer_medium_demons_count', 'completed_platformer_hard_demons_count', 'completed_platformer_insane_demons_count', 'completed_platformer_extreme_demons_count', 'completed_gauntlet_levels_count', 'completed_gauntlet_demon_levels_count'];

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}