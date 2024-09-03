<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

class GameAccountLoginRequest extends GameRequest
{
	public function rules(): array
	{
		return [
			'userName' => [
				'required',
				'string',
				Rule::exists(Account::class, 'name')
			],
			'password' => [
				'required',
				'string'
			],
			'udid' => [
				'required',
				'string'
			],
			'sID' => [
				'nullable',
				'string'
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