<?php

namespace App\Exceptions;

class StorageException extends BaseException
{
    protected string $log_channel = 'gdcn';

    protected function formatMessage(string $message): string
    {
        return '存储异常: ' . $message;
    }

    public static function invalidConfig(): StorageException
    {
        return new static('无效的配置');
    }

    public static function notFound(): StorageException
    {
        return new static('内容不存在(或未找到)');
    }
}
