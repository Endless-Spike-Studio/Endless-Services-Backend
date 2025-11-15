<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashAccountDataTypes;
use Illuminate\Validation\Rule;

class GameFetchAccountDataBaseUrlRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			'accountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			'type' => [
				'required',
				'integer',
				Rule::enum(GeometryDashAccountDataTypes::class)
			],
			...$this->secret()
		];
	}
}