<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\LevelRatingDemonDifficulty;
use App\Enums\GDCS\LevelRatingDifficulty;
use App\Http\Controllers\GDCS\HelperController;
use App\Models\NGProxy\Song;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Level extends Model
{
    protected $table = 'gdcs_levels';
    protected $fillable = ['user_id', 'game_version', 'name', 'desc', 'downloads', 'likes', 'version', 'length', 'audio_track', 'song_id', 'auto', 'password', 'original_level_id', 'two_player', 'objects', 'coins', 'requested_stars', 'unlisted', 'ldm', 'extra_string', 'level_info'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function song(): HasOne
    {
        return $this->hasOne(Song::class, 'song_id', 'song_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LevelComment::class, 'level_id');
    }

    public function rate(int $stars): Model|LevelRating
    {
        $diff = HelperController::guessLevelRatingDifficultyFromStars($stars);

        return $this->rating()
            ->firstOrCreate([
                'stars' => $stars,
                'difficulty' => $diff,
                'auto' => $diff === LevelRatingDifficulty::AUTO,
                'demon' => $diff === LevelRatingDifficulty::DEMON
            ]);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(LevelRating::class)
            ->withDefault([
                'stars' => 0,
                'difficulty' => LevelRatingDifficulty::NA,
                'demon_difficulty' => LevelRatingDemonDifficulty::HARD,
                'featured_score' => 0,
                'coin_verified' => false,
                'epic' => false,
                'auto' => false,
                'demon' => false
            ]);
    }

    public function original(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'original_level_id', 'id');
    }
}
