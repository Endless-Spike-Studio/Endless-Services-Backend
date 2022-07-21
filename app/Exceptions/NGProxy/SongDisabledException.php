<?php

namespace App\Exceptions\NGProxy;

use App\Enums\Response;
use Exception;
use Illuminate\Support\Facades\Request;

class SongDisabledException extends Exception
{
    public function render(): int|array
    {
        return Request::expectsJson() ? [
            'status' => false,
            'message' => __('messages.song_disabled'),
        ] : Response::SONG_DISABLED->value;
    }
}
