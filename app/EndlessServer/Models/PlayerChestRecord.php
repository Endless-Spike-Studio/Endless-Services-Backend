<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerChestRecord extends Model
{
	protected $table = 'endless_server.player_chest_records';

	protected $fillable = ['player_id', 'type', 'orbs', 'diamonds', 'shards', 'keys'];
}