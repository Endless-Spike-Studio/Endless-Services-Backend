<?php

namespace App\Exceptions;

use Throwable;

class NewGroundsProxyException extends BaseException
{
    protected string $log_channel = 'gdcn';

    public function __construct(
        string                          $message = null,
        int                             $code = 0,
        Throwable                       $previous = null,
        int                             $http_code = 500,
        array                           $log_context = [],
        public readonly int|string|null $response = null
    )
    {
        parent::__construct($message, $code, $previous, $http_code, $log_context);
    }

    protected function formatMessage(string $message): string
    {
        return '[NGProxy]' . $message;
    }
}
