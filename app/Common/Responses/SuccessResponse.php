<?php

namespace App\Common\Responses;

use Illuminate\Contracts\Support\Responsable;

class SuccessResponse implements Responsable
{
	public function __construct(
		protected int   $code = 200,
		protected mixed $data = null
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