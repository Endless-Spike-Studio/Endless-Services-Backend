<?php

namespace App\Exceptions;

class ResponseException extends BaseException
{
    protected string $log_channel = 'gdcn';

    public static function invalid(string $response): ResponseException
    {
        $e = new static('数据无效');
        $e->log_context = ['data' => $response];
        return $e;
    }

    protected function formatMessage(string $message): string
    {
        return '响应异常: ' . $message;
    }
}
