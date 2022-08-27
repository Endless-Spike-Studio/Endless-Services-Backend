<?php

namespace App\Exceptions;

class GeometryDashChineseServerException extends BaseException
{
    protected string $log_channel = 'gdcn';

    protected function formatMessage(string $message): string
    {
        return '[GDCS]' . $message;
    }
}
