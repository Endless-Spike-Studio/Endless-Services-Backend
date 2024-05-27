<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountUnblockRequest extends Request
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
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
		];
	}
}
