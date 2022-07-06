<?php

namespace App\Exceptions\NGProxy;

use App\Enums\Response;
use Exception;
use Illuminate\Support\Facades\Request;

class SongDisabledException extends Exception
{
    public function render(): int|array
    {
        if (Request::expectsJson()) {
            return [
                'status' => false,
                'message' => 'Song is disabled',
            ];
        }

        return Response::SONG_DISABLED->value;
    }
}
