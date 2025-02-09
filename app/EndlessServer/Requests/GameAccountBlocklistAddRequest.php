<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameAccountBlocklistAddRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->identifies(),
			...$this->auth_gjp2(),
			'targetAccountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id'),
				'different:accountID'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}