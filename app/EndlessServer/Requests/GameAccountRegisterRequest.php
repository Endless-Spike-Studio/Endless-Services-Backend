<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

class GameAccountRegisterRequest extends GameRequest
{
	public function rules(): array
	{
		return [
			'userName' => [
				'required',
				'string',
				'alpha_dash:ascii',
				Rule::unique(Account::class, 'name')
			],
			'password' => [
				'required',
				'string'
			],
			'email' => [
				'required',
				'string',
				'email',
				Rule::unique(Account::class)
			],
			'secret' => [
				'required',
				'string',
				Rule::in([
					GeometryDashSecrets::ACCOUNT->value
				])
			]
		];
	}
}