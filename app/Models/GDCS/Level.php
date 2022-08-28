<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\LevelRatingDifficulty;
use App\Models\NGProxy\Song;
use App\Services\Storage\GameLevelDataStorageService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Level extends Model
{
    protected $table = 'gdcs_levels';

    protected $fillable = ['user_id', 'game_version', 'name', 'desc', 'downloads', 'likes', 'version', 'length', 'audio_track', 'song_id', 'auto', 'password', 'original_level_id', 'two_player', 'objects', 'coins', 'requested_stars', 'unlisted', 'ldm', 'extra_string', 'level_info'];

    public function data(): Attribute
    {
        return new Attribute(
            fn() => (new GameLevelDataStorageService)->get(['id' => $this->id]),
            fn(string $value) => (new GameLevelDataStorageService)->put(['id' => $this->id], $value)
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->user();
    }

    public function song(): HasOne
    {
        return $this->hasOne(Song::class, 'song_id', 'song_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LevelComment::class, 'level_id');
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
                'demon' => false,
            ]);
    }

    public function original(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'original_level_id', 'id');
    }

    public function daily(): HasOne
    {
        return $this->hasOne(DailyLevel::class);
    }

    public function weekly(): HasOne
    {
        return $this->hasOne(WeeklyLevel::class);
    }
}
