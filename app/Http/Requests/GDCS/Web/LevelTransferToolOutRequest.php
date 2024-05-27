<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;
use App\Models\GDCS\AccountLink;
use Illuminate\Validation\Rule;

class LevelTransferToolOutRequest extends Request
{
	public function rules(): array
	{
		return [
			'linkID' => [
				'required',
				'integer',
				Rule::exists(AccountLink::class, 'id')
			],
			'password' => [
				'required',
				'string'
			]
		];
	}
}
