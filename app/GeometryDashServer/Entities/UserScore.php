<?php

namespace App\GeometryDashServer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserScore extends Model
{
	protected $table = 'gdcs_user_scores';
	protected $fillable = ['stars', 'demons', 'diamonds', 'icon', 'color1', 'color2', 'icon_type', 'coins', 'user_coins', 'special', 'acc_icon', 'acc_ship', 'acc_ball', 'acc_bird', 'acc_dart', 'acc_robot', 'acc_glow', 'acc_spider', 'acc_explosion', 'creator_points'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
