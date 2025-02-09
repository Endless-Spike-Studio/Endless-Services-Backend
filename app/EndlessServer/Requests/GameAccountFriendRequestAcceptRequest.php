<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountFriendRequest;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameAccountFriendRequestAcceptRequest extends GameRequest
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
				Rule::exists(Account::class, 'id')
			],
			'requestID' => [
				'required',
				'integer',
				Rule::exists(AccountFriendRequest::class, 'id')
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}