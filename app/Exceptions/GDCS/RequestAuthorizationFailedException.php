<?php

namespace App\Exceptions\GDCS;

use Exception;

class RequestAuthorizationFailedException extends Exception
{
    public function render(): int
    {
        if (is_numeric($this->message)) {
            return $this->message;
        }

        return \App\Enums\Response::REQUEST_AUTHORIZATION_FAILED->value;
    }
}
