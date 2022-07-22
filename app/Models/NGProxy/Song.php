<?php

namespace App\Models\NGProxy;

use App\Exceptions\StorageContentMissingException;
use App\Services\StorageService;
use GeometryDashChinese\GeometryDashObject;
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

    public function getDownloadUrlAttribute(): string
    {
        return route('api.ngproxy.download', $this->song_id);
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
     * @throws StorageContentMissingException
     */
    public function download(): StreamedResponse
    {
        /** @var StorageService $storage */
        $storage = app('storage:ngproxy.song_data');

        return $storage->download($this->song_id);
    }
}
