<?php

namespace App\Models\GDCS;

use GDCN\GDObject\GDObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomSong extends Model
{
    protected $table = 'gdcs_custom_songs';

    protected $fillable = ['name', 'artist_name', 'size', 'download_url'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function getObjectAttribute(): string
    {
        return GDObject::merge([
            1 => $this->id + config('gdcs.custom_song_offset', 10000000),
            2 => $this->name,
            3 => 8,
            4 => $this->artist_name,
            5 => $this->size,
            10 => $this->download_url ?? route('api.gdcs.customSong.download', ['id' => $this->id]),
        ], '~|~');
    }
}
