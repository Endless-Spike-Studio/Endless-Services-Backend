<?php

namespace App\Common\Responses;

class FailedResponse extends SuccessResponse
{
	public function __construct(
		protected string $message,
		protected int    $code = 500,
		protected mixed  $data = null
	)
	{
		parent::__construct($this->code, $this->data);
	}

	public function toResponse($request): array
	{
		return [
			...parent::toResponse($request),
			'error' => [
				'message' => $this->message
			]
		];
	}
}