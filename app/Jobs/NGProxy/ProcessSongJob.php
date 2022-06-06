<?php

namespace App\Jobs\NGProxy;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSongJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int    $id,
        protected string $url
    )
    {

    }

    public function handle(): void
    {
        $storage = app('storage:ngproxy.song_data');

        if (!$storage->allExists($this->id)) {
            $data = app('proxy')
                ->withOptions([
                    'decode_content' => false
                ])
                ->get(
                    urldecode($this->url)
                )
                ->body();

            $storage->put($this->id, $data);
        }
    }
}
