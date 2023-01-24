<?php

namespace App\Exceptions;

class GeometryDashChineseServerException extends BaseException
{
    protected string $logChannel = 'gdcn';

    protected function formatMessage(string $message): string
    {
        return '[GDCS]' . $message;
    }
}
