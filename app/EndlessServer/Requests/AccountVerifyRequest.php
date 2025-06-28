<?php

namespace App\EndlessServer\Requests;

use App\Api\Requests\ApiRequest;

class AccountVerifyRequest extends ApiRequest
{
	public function rules(): array
	{
		return [
			'_' => [
				'required',
				'string'
			]
		];
	}
}