<?php

namespace App\Exceptions;

use Exception;

class StorageContentMissingException extends Exception
{
	public function render(): void
	{
		abort(404);
	}
}
