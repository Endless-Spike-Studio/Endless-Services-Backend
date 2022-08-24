<?php

namespace App\Jobs\NGProxy;

use App\Exceptions\NewGroundsProxyException;
use App\Models\NGProxy\Song;
use App\Services\ProxyService;
use App\Services\Storage\SongStorageService;
use GuzzleHttp\RequestOptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Psr\Http\Client\ClientExceptionInterface;

class ProcessSongJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Song $song)
    {
        $this->middleware = [
            new WithoutOverlapping($song->id)
        ];
    }

    /**
     * @throws NewGroundsProxyException
     */
    public function handle(): void
    {
        if (!app(SongStorageService::class)->allValid(['id' => $this->song->song_id])) {
            try {
                $decodedUrl = urldecode($this->song->original_download_url);
                $url = str_replace('https://', 'http://', $decodedUrl);

                $this->song->data = ProxyService::instance()
                    ->withOptions([
                        RequestOptions::DECODE_CONTENT => false
                    ])
                    ->retry(3, 1000)
                    ->timeout(600)
                    ->get($url)
                    ->body();
            } catch (ClientExceptionInterface $ex) {
                throw new NewGroundsProxyException(
                    __('gdcn.song.error.process_failed_request_error', [
                        'reason' => $ex->getMessage()
                    ])
                );
            }
        }
    }
}
