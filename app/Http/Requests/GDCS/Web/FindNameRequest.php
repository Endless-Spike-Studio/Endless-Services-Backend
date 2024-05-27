<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;
use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class FindNameRequest extends Request
{
	public function rules(): array
	{
		return [
			'email' => [
				'required',
				'string',
				Rule::exists(Account::class)
			]
		];
	}
}
