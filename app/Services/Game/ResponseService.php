<?php

namespace App\Services\Game;

use Illuminate\Support\Str;

class ResponseService
{
    public static function check(string $data): bool
    {
        return empty($data) || Str::startsWith($data, 'error') || Str::startsWith($data, '<') || Str::matchAll('/^(-)(\d+)$/', $data)->isNotEmpty();
    }
}
