<?php

namespace App\Services\Game;

use App\Exceptions\ResponseException;
use Illuminate\Support\Str;

class ResponseService extends BaseGameService
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
            || (is_numeric($data) && $data <= 0)
        ) {
            throw new ResponseException(__('gdcn.response.error.invalid'), logContext: ['data' => $data]);
        }
    }
}
