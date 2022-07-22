<?php

namespace App\Exceptions\GDCS;

use App\Enums\Response;
use Exception;

class RequestAuthorizationFailedException extends Exception
{
    public function render(): int
    {
        if (is_numeric($this->message)) {
            return $this->message;
        }

        return Response::REQUEST_AUTHORIZATION_FAILED->value;
    }
}
