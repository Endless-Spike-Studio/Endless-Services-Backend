<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;
use App\Models\GDCS\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AccountEditRequest extends Request
{
	public function rules(): array
	{
		$currentAccountID = Auth::guard('gdcs')->id();

		return [
			'name' => [
				'required',
				'string',
				Rule::unique(Account::class)->ignore($currentAccountID)
			],
			'email' => [
				'required',
				'string',
				'email',
				Rule::unique(Account::class)->ignore($currentAccountID)
			]
		];
	}
}
