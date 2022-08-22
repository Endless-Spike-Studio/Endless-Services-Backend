<?php

namespace App\Exceptions\NGProxy;

use App\Exceptions\BaseException;
use App\Http\Traits\HasMessage;

class SongException extends BaseException
{
    use HasMessage;

    protected function initialize(): void
    {
        $this->log_channel = 'gdcn';
        $this->http_code = 503;
    }

    public static function notFound(): SongException
    {
        return new static('歌曲不存在(或未找到)');
    }

    public static function disabled(): SongException
    {
        return new static('歌曲已被禁用');
    }

    public static function processing(): SongException
    {
        return new static('歌曲处理异常, 请稍后再试');
    }

    protected function formatMessage(string $message): string
    {
        return 'NGProxy 歌曲异常: ' . $message;
    }
}
