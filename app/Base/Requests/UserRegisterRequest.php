<?php

namespace App\Base\Requests;

use App\Base\Models\User;
use App\Common\Requests\ApiRequest;
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