<?php

namespace App\Models\NGProxy;

use App\Exceptions\NewGroundsProxyException;
use App\Exceptions\StorageException;
use App\Services\Game\ObjectService;
use App\Services\ProxyService;
use App\Services\Storage\SongStorageService;
use GuzzleHttp\RequestOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Song extends Model
{
    protected $table = 'ngproxy_songs';

    protected $fillable = ['song_id', 'name', 'artist_id', 'artist_name', 'size', 'disabled', 'original_download_url'];

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'download_url' => $this->download_url,
            'valid' => app(SongStorageService::class)->allValid(['id' => $this->song_id])
        ];
    }

    public function data(): Attribute
    {
        $storage = app(SongStorageService::class);
        $data = ['id' => $this->song_id];

        return new Attribute(
            fn() => $storage->get($data),
            fn(string $value) => $storage->put($data, $value)
        );
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('api.ngproxy.download', $this->song_id);
    }

    public function getObjectAttribute(): string
    {
        return ObjectService::merge([
            1 => $this->song_id,
            2 => $this->name,
            3 => $this->artist_id,
            4 => $this->artist_name,
            5 => $this->size,
            10 => $this->download_url,
        ], '~|~');
    }

    /**
     * @throws NewGroundsProxyException
     * @throws StorageException
     */
    public function download(): StreamedResponse
    {
        try {
            $url = urldecode($this->original_download_url);
            $url = str_replace('https://', 'http://', $url);
            $storage = app(SongStorageService::class);
            $data = ['id' => $this->song_id];

            if (!$storage->allValid($data)) {
                $response = ProxyService::instance()
                    ->asForm()
                    ->withUserAgent(null)
                    ->withOptions([
                        RequestOptions::DECODE_CONTENT => false
                    ])->get($url);

                if (!$response->successful()) {
                    throw new NewGroundsProxyException(__('gdcn.song.error.process_failed'));
                }

                $this->data = $response->body();
            }

            return $storage->download($data);
        } catch (ClientExceptionInterface $ex) {
            throw new NewGroundsProxyException(
                __('gdcn.song.error.process_failed_request_error', [
                    'reason' => $ex->getMessage()
                ])
            );
        }
    }
}
