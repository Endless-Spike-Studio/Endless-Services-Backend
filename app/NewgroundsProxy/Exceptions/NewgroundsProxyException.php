<?php

namespace App\NewgroundsProxy\Exceptions;

use App\Exceptions\BaseException;

class NewgroundsProxyException extends BaseException
{
	protected function formatMessage(string $message): string
	{
		return '[NewgroundsProxy]' . $message;
	}
}