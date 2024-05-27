<?php

namespace App\Exceptions;

class StorageException extends BaseException
{
	protected string $logChannel = 'gdcn';

	protected function formatMessage(string $message): string
	{
		return '[存储] 异常: ' . $message;
	}
}
