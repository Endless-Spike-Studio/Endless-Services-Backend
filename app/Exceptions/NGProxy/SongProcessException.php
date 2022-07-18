<?php

namespace App\Exceptions\NGProxy;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SongProcessException extends HttpException
{
    public static function failed(...$params): SongProcessException
    {
        return new static(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            __('messages.song_process_failed'),
            $params
        );
    }
}
