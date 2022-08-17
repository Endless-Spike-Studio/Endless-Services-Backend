<?php

namespace App\Exceptions;

class ResponseException extends BaseException
{
    protected string $log_channel = 'gdcn';

    public static function invalid(): ResponseException
    {
        return new static('响应无效');
    }

    protected function formatMessage(string $message): string
    {
        return '响应异常: ' . $message;
    }
}
