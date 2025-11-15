<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelNormalScore extends Model
{
	protected $table = 'endless_server.level_normal_scores';

	protected $fillable = ['account_id', 'level_id', 'percent', 'attempts', 'clicks', 'attempt_seconds', 'coins', 'special_id'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}