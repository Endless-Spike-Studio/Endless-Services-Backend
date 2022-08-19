<?php

namespace App\Services\Game;

use App\Exceptions\ResponseException;
use Illuminate\Support\Str;

class ResponseService
{
    /**
     * @throws ResponseException
     */
    public static function check(string $data): void
    {
        if (
            empty($data)
            || Str::startsWith($data, 'error')
            || Str::startsWith($data, '<')
            || Str::matchAll('/^(-)(\d+)$/', $data)->isNotEmpty()
        ) {
            throw ResponseException::invalid($data);
        }
    }
}
