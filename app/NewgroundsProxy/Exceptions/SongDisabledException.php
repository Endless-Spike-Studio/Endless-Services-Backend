<?php

namespace App\NewgroundsProxy\Exceptions;

use App\GeometryDash\Enums\Response;
use App\NewgroundsProxy\Entities\Song;
use Throwable;

class SongDisabledException extends SongException
{
	public function __construct(
		string                   $message = null,
		int                      $code = 0,
		Throwable                $previous = null,

		int                      $http_code = 500,
		array                    $http_headers = [],

		string                   $game_response = Response::FAILED->value,

		protected readonly ?Song $song = null
	)
	{
		parent::__construct($message, $code, $previous, $http_code, $http_headers, $game_response);
	}
}