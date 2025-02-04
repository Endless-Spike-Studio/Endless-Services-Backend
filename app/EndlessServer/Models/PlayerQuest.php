<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerQuest extends Model
{
	protected $table = 'endless_server.player_quests';

	protected $fillable = ['player_id', 'name', 'collect_type', 'collect_count', 'reward_count'];
}