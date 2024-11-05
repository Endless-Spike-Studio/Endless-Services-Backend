<?php

namespace App\EndlessProxy\Exceptions;

use App\Common\Exceptions\ApplicationException;
use Throwable;

class SongResolveException extends ApplicationException
{
	public function __construct(
		protected                     $message = '',
		protected                     $code = 0,
		protected readonly ?Throwable $previous = null
	)
	{
		parent::__construct(404, $message, $previous, [], $code);
	}
}