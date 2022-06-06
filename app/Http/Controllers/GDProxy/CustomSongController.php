<?php

namespace App\Http\Controllers\GDProxy;

use App\Exceptions\StorageContentMissingException;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomSongController extends Controller
{
    /**
     * @throws StorageContentMissingException
     */
    public function download(int $id): StreamedResponse
    {
        return app('storage:gdproxy.song_data')
            ->download($id);
    }
}
