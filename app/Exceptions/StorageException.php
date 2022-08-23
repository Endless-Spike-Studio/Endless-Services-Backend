<?php

namespace App\Exceptions;

use Throwable;

class StorageException extends BaseException
{
    public function __construct(string $message = null, int $code = 0, Throwable $previous = null, public array $log_context = [])
    {
        parent::__construct($message, $code, $previous, log_channel: 'gdcn');
    }

    protected function formatMessage(string $message): string
    {
        return '[存储] 异常: ' . $message;
    }
}
