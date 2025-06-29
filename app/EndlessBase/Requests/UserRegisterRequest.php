<?php

namespace App\EndlessBase\Requests;

use App\Api\Requests\ApiRequest;
use App\EndlessBase\Models\User;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends ApiRequest
{
	public function rules(): array
	{
		return [
			'name' => [
				'required',
				'string',
				'alpha_dash:ascii',
				Rule::unique(User::class)
			],
			'email' => [
				'required',
				'string',
				'email',
				Rule::unique(User::class)
			],
			'password' => [
				'required',
				'string',
				'confirmed'
			]
		];
	}
}