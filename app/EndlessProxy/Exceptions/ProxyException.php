<?php

namespace App\EndlessProxy\Exceptions;

use App\Common\Exceptions\ApplicationException;
use Throwable;

class ProxyException extends ApplicationException
{
	public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
	{
		parent::__construct(404, $message, $previous, [], $code);
	}
}