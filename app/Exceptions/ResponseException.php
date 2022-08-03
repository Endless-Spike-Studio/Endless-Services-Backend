<?php

namespace App\Exceptions;

class ResponseException extends BaseException
{
    protected string $log_channel = 'gdcn';

    protected function formatMessage(string $message): string
    {
        return '响应异常: ' . $message;
    }

    public static function invalid(): ResponseException
    {
        return new static('响应无效');
    }
}
