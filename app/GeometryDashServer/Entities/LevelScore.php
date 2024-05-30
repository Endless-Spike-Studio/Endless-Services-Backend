<?php

namespace App\GeometryDashServer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelScore extends Model
{
	protected $table = 'gdcs_level_scores';
	protected $fillable = ['account_id', 'level_id', 'attempts', 'percent', 'coins'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}
}
