<?php

namespace App\Models\GDProxy;

use App\Models\NGProxy\Song;
use Database\Factories\GDProxy\LevelSongReplaceFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class LevelSongReplace extends Model
{
    use HasFactory;

    protected $table = 'gdproxy_level_song_replaces';

    protected static function newFactory(): LevelSongReplaceFactory
    {
        return new LevelSongReplaceFactory();
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class, 'song_id', 'song_id');
    }
}
