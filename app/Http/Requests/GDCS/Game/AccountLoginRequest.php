<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountLoginRequest extends Request
{
	public function authorize(): bool
	{
		return $this->auth() && !empty($this->account);
	}

	public function rules(): array
	{
		return [
			'userName' => [
				'required',
				'string',
				Rule::exists(Account::class, 'name'),
			],
			'password' => [
				'required',
				'string',
			],
			'udid' => [
				'required',
				'string',
			],
			'sID' => [
				'sometimes',
				'required',
				'integer',
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfv3899gc9',
			],
		];
	}
}
