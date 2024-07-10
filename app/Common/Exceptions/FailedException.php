<?php

namespace App\Common\Exceptions;

use App\Common\Responses\FailedResponse;
use Exception;

class FailedException extends Exception
{
	public function __construct(
		protected       $message,
		protected       $code = 500,
		protected       $previous = null,
		protected mixed $data = null
	)
	{
		parent::__construct($this->message, $this->code, $this->previous);
	}

	public function render(): FailedResponse
	{
		return new FailedResponse($this->message, $this->code, $this->data);
	}
}