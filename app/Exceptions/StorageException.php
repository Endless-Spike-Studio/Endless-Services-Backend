<?php

namespace App\Exceptions;

class StorageException extends BaseException
{
    protected string $log_channel = 'gdcn';

    protected function formatMessage(string $message): string
    {
        return '[存储] 异常: ' . $message;
    }
}
