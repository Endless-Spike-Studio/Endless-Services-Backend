<?php

namespace App\Http\Controllers\GDProxy;

use App\Exceptions\StorageContentMissingException;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\GDProxy\CustomSongController as CustomSongDataController;

class CustomSongController extends Controller
{
    /**
     * @throws StorageContentMissingException
     */
    public function download(int $id): StreamedResponse
    {
        return app(CustomSongDataController::class)
            ->download($id);
    }
}
