<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use App\Models\GDCS\AccountFriendRequest;
use Illuminate\Validation\Rule;

class AccountFriendRequestAcceptRequest extends Request
{
	public function authorize(): bool
	{
		return $this->auth() && !empty($this->account);
	}

	public function rules(): array
	{
		return [
			'gameVersion' => [
				'required',
				'integer',
			],
			'binaryVersion' => [
				'required',
				'integer',
			],
			'gdw' => [
				'required',
				'boolean',
			],
			'accountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id'),
				Rule::exists(AccountFriendRequest::class, 'target_account_id'),
			],
			'gjp' => [
				'required',
				'string',
			],
			'targetAccountID' => [
				'different:accountID',
				'required',
				'integer',
				Rule::exists(Account::class, 'id'),
				Rule::exists(AccountFriendRequest::class, 'account_id'),
			],
			'requestID' => [
				'required',
				'integer',
				Rule::exists(AccountFriendRequest::class, 'id'),
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
		];
	}
}
