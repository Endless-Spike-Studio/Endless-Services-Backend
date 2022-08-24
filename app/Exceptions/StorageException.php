<?php

namespace App\Exceptions;

use Throwable;

class StorageException extends BaseException
{
    protected string $log_channel = 'gdcn';

    public function __construct(string $message = null, int $code = 0, Throwable $previous = null, public array $log_context = [])
    {
        parent::__construct($message, $code, $previous);
    }

    protected function formatMessage(string $message): string
    {
        return '[存储] 异常: ' . $message;
    }
}
