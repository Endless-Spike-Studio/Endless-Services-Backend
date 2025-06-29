<?php

namespace App\EndlessBase\Requests;

use App\Api\Requests\ApiRequest;
use App\EndlessBase\Models\User;
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