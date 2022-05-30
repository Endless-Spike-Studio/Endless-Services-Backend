<?php

namespace App\Exceptions;

use App\Enums\Response;
use Exception;

class InvalidResponseException extends Exception
{
    public function render(): int
    {
        return Response::INVALID_RESPONSE->value;
    }
}
