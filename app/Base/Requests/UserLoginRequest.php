<?php

namespace App\Base\Requests;

use App\Base\Models\User;
use App\Common\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class UserLoginRequest extends ApiRequest
{
	public function rules(): array
	{
		return [
			'name' => [
				'required',
				'string',
				Rule::exists(User::class)
			],
			'password' => [
				'required',
				'string'
			]
		];
	}
}