<?php

namespace App\Models\GDProxy;

use Database\Factories\GDProxy\CustomSongFactory;
use GeometryDashChinese\GeometryDashObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomSong extends Model
{
    use HasFactory;

    protected $table = 'gdproxy_custom_songs';

    protected static function newFactory(): CustomSongFactory
    {
        return new CustomSongFactory();
    }

    public function getObjectAttribute(): string
    {
        return GeometryDashObject::merge([
            1 => $this->id + config('gdproxy.custom_song_offset', 10000000),
            2 => $this->name,
            3 => 8,
            4 => $this->artist_name,
            5 => $this->size,
            10 => $this->download_url ?? route('api.gdproxy.customSong.download', ['id' => $this->id]),
        ], '~|~');
    }
}
