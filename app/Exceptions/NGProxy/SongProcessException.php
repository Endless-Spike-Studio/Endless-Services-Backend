<?php

namespace App\Exceptions\NGProxy;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class SongProcessException extends HttpException
{
    public function __construct(int $statusCode = 500, string $message = null, Throwable $previous = null, array $headers = [], int $code = 0)
    {
        $message ??= __('messages.song_process_failed');
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
