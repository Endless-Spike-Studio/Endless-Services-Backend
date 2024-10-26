<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

class GameAccountRegisterRequest extends GameRequest
{
	use GameRequestRules;

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
			...$this->secret(GeometryDashSecrets::ACCOUNT)
		];
	}

	public function messages(): array
	{
		return [
			'userName.unique' => GeometryDashResponses::ACCOUNT_REGISTER_FAILED_USERNAME_ALREADY_EXISTS->value,
			'email.email' => GeometryDashResponses::ACCOUNT_REGISTER_FAILED_EMAIL_IS_INVALID->value,
			'email.unique' => GeometryDashResponses::ACCOUNT_REGISTER_FAILED_EMAIL_ALREADY_EXISTS->value
		];
	}
}