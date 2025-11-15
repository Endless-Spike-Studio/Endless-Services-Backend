<?php

namespace App\Api\Responses;

use Illuminate\Contracts\Support\Responsable;

class SuccessResponse implements Responsable
{
	public function __construct(
		protected mixed $data = null,
		protected int   $code = 200
	)
	{

	}

	public function toResponse($request): array
	{
		return [
			'code' => $this->code,
			'data' => $this->data
		];
	}
}