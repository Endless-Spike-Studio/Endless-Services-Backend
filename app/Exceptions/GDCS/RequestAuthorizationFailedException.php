<?php

namespace App\Exceptions\GDCS;

use App\Enums\Response;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class RequestAuthorizationFailedException extends Exception
{
    public function report(): void
    {
        Log::error('GDCS: 请求身份验证失败', [
            'path' => Request::fullUrl(),
        ]);
    }

    public function render(): int
    {
        if (is_numeric($this->message)) {
            return $this->message;
        }

        return Response::REQUEST_AUTHORIZATION_FAILED->value;
    }
}
