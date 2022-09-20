<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;

class LevelDemonDifficultySuggestion extends Model
{
    protected $table = 'gdcs_level_demon_difficulty_suggestions';
    protected $fillable = ['user_id', 'level_id', 'demon_diff', 'ip'];
}
