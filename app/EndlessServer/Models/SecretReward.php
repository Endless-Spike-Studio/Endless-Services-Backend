<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SecretReward extends Model
{
	protected $table = 'endless_server.secret_rewards';

	public function records(): HasMany
	{
		return $this->hasMany(SecretRewardRecord::class);
	}

	protected function casts(): array
	{
		return [
			'rewards' => 'array'
		];
	}
}