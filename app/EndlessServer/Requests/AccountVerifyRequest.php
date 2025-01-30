<?php

namespace App\EndlessServer\Requests;

use App\Common\Requests\ApiRequest;

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