<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerData extends Model
{
	protected $table = 'endless_server.player_data';

	protected $fillable = ['stars', 'moons', 'demons', 'diamonds', 'icon_id', 'icon_type', 'coins', 'user_coins', 'cube_id', 'ship_id', 'ball_id', 'bird_id', 'dart_id', 'robot_id', 'glow_id', 'spider_id', 'explosion_id', 'swing_id', 'jetpack_id', 'color1', 'color2', 'color3', 'special'];

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
}