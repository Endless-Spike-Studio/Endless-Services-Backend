<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecretRewardRecord extends Model
{
	protected $table = 'endless_server.secret_reward_records';

	protected $fillable = ['player_id', 'secret_reward_id'];

	public function secretReward(): BelongsTo
	{
		return $this->belongsTo(SecretReward::class);
	}
}