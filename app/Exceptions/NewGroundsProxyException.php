<?php

namespace App\Exceptions;

class NewGroundsProxyException extends BaseException
{
    protected string $log_channel = 'gdcn';

    protected function formatMessage(string $message): string
    {
        return '[NGProxy]' . $message;
    }
}
