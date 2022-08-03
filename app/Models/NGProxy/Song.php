<?php

namespace App\Models\NGProxy;

use App\Exceptions\StorageException;
use App\Services\Storage\SongStorageService;
use GeometryDashChinese\GeometryDashObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Song extends Model
{
    protected $table = 'ngproxy_songs';
    protected $fillable = ['song_id', 'name', 'artist_id', 'artist_name', 'size', 'disabled'];

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'download_url' => $this->download_url,
        ];
    }

    public function data(): Attribute
    {
        return new Attribute(
            fn() => app(SongStorageService::class)->get(['id' => $this->song_id]),
            fn(string $value) => app(SongStorageService::class)->put(['id' => $this->song_id], $value)
        );
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('ngproxy.download', $this->song_id);
    }

    public function getObjectAttribute(): string
    {
        return GeometryDashObject::merge([
            1 => $this->song_id,
            2 => $this->name,
            3 => $this->artist_id,
            4 => $this->artist_name,
            5 => $this->size,
            10 => $this->download_url,
        ], '~|~');
    }

    /**
     * @throws StorageException
     */
    public function download(): StreamedResponse
    {
        return app(SongStorageService::class)->download(['id' => $this->song_id]);
    }
}
