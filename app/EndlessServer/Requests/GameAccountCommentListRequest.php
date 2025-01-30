<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Validation\Rule;

class GameAccountCommentListRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->identifies(),
			'accountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			'page' => [
				'required',
				'integer'
			],
			'total' => [
				'nullable',
				'integer'
			],
			...$this->secret()
		];
	}
}