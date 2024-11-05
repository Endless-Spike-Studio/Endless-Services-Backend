<?php

namespace App\Common\Responses;

class FailedResponse extends SuccessResponse
{
	public function __construct(
		protected readonly string $message,
		protected readonly int    $code = 500,
		protected readonly mixed  $data = null
	)
	{
		parent::__construct($this->data, $this->code);
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