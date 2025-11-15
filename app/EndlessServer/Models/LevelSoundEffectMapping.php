<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelSoundEffectMapping extends Model
{
	protected $table = 'endless_server.level_sound_effect_mappings';

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}

	public function soundEffect(): BelongsTo
	{
		return $this->belongsTo(SoundEffect::class);
	}
}