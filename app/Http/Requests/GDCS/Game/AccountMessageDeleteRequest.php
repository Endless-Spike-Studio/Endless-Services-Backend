<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use App\Models\GDCS\AccountMessage;
use Illuminate\Validation\Rule;

class AccountMessageDeleteRequest extends Request
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
			'messageID' => [
				'required_without:messages',
				'integer',
				Rule::exists(AccountMessage::class, 'id'),
			],
			'messages' => [
				'required_without:messageID',
				'string',
				Rule::exists(AccountMessage::class, 'id'),
			],
			'isSender' => [
				'sometimes',
				'boolean',
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
		];
	}
}
