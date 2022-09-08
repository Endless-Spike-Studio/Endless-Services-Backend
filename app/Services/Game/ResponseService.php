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
            || Str::matchAll('/^(-)(\d+)$/', $data)->isNotEmpty()
        ) {
            throw new ResponseException(__('gdcn.response.error.invalid'), log_context: ['data' => $data]);
        }
    }
}
