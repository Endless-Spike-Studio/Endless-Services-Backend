<?php

namespace App\Api\Exceptions;

use App\Api\Responses\FailedResponse;
use Exception;
use Throwable;

class ApiException extends Exception
{
	public function __construct(
		protected                     $message,
		protected                     $code = 500,
		protected readonly ?Throwable $previous = null,
		protected readonly mixed      $data = null
	)
	{
		parent::__construct($this->message, $this->code, $this->previous);
	}

	public function render(): FailedResponse
	{
		return new FailedResponse($this->message, $this->code, $this->data);
	}
}