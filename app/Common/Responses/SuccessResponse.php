<?php

namespace App\Common\Responses;

use Illuminate\Contracts\Support\Responsable;

class SuccessResponse implements Responsable
{
	public function __construct(
		protected readonly mixed $data = null,
		protected readonly int   $code = 200
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