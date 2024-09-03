<?php

namespace App\EndlessServer\Exceptions;

use Exception;
use Illuminate\Support\Facades\Request;
use Throwable;

class EndlessServerGameException extends Exception
{
	public function __construct(
		string    $message = null,
		int       $code = 0,
		Throwable $previous = null
	)
	{
		parent::__construct($message, $code, $previous);
	}

	public function render()
	{
		if (Request::has('secret')) {
			return response($this->code);
		}

		abort(500);
	}
}
