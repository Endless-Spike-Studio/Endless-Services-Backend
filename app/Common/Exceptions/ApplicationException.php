<?php

namespace App\Common\Exceptions;

use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApplicationException extends HttpException
{
	public function render()
	{
		if (Request::expectsJson()) {
			return [
				'error' => $this->getMessage()
			];
		}

		abort(
			$this->getStatusCode(),
			$this->getMessage(),
			$this->getHeaders()
		);
	}
}