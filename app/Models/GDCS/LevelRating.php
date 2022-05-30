<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\LevelRatingDemonDifficulty;
use App\Enums\GDCS\LevelRatingDifficulty;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LevelRating extends Model
{
    protected $table = 'gdcs_level_ratings';
    protected $fillable = ['level_id', 'stars', 'difficulty', 'featured_score', 'epic', 'coin_verified', 'demon_difficulty', 'auto', 'demon'];

    protected $casts = [
        'difficulty' => LevelRatingDifficulty::class,
        'featured' => 'boolean',
        'epic' => 'boolean',
        'coin_verified' => 'boolean',
        'demon_difficulty' => LevelRatingDemonDifficulty::class,
        'auto' => 'boolean',
        'demon' => 'boolean'
    ];
}
