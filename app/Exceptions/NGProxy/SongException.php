<?php

namespace App\Exceptions\NGProxy;

use App\Exceptions\BaseException;
use Throwable;

class SongException extends BaseException
{
    public function __construct(string $message = null, int $code = 0, Throwable $previous = null, public array $log_context = [])
    {
        parent::__construct($message, $code, $previous, log_channel: 'gdcn');
    }

    protected function formatMessage(string $message): string
    {
        return 'NGProxy 歌曲异常: ' . $message;
    }
}
