<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Validation\Rule;

class GamePlayerInfoFetchRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->auth_gjp2(),
			'targetAccountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			...$this->secret()
		];
	}
}