<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidResponseException;
use Illuminate\Support\Str;

class HelperController extends Controller
{
    /**
     * @throws InvalidResponseException
     */
    public static function checkResponse(string $response): void
    {
        if (
            empty($response)
            || Str::startsWith($response, 'error')
            || Str::startsWith($response, '<')
            || Str::matchAll('/^(-)(\d+)$/', $response)->isNotEmpty()
        ) {
            throw new InvalidResponseException();
        }
    }
}
