<?php

namespace App\Exceptions;

class ResponseException extends BaseException
{
    protected function initialize(): void
    {
        $this->log_channel = 'gdcn';
        $this->http_code = 503;
    }

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
