<?php

namespace App\GeometryDashServer\Entities;

use Illuminate\Database\Eloquent\Model;

class LevelStarSuggestion extends Model
{
	protected $table = 'gdcs_level_star_suggestions';
	protected $fillable = ['user_id', 'level_id', 'stars', 'ip'];
}
