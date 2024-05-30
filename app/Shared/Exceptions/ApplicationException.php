<?php

namespace App\Shared\Exceptions;

use App\GeometryDash\Enums\Response as GameResponse;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Throwable;

class ApplicationException extends Exception
{
	public function __construct(
		string                    $message = null,
		int                       $code = 0,
		Throwable                 $previous = null,

		protected readonly int    $http_code = 500,
		protected readonly array  $http_headers = [],

		protected readonly string $game_response = GameResponse::FAILED->value
	)
	{
		parent::__construct($message, $code, $previous);
	}

	public function render(): Response
	{
		if (Request::ajax() || Request::wantsJson()) {
			return response([
				'error' => $this->message
			]);
		}

		if (empty(Request::userAgent())) {
			return response($this->game_response);
		}

		return response($this->message, $this->http_code, $this->http_headers);
	}
}