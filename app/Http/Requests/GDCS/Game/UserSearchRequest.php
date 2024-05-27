<?php

namespace App\Http\Requests\GDCS\Game;

class UserSearchRequest extends Request
{
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
			'str' => [
				'required',
				'string',
			],
			'page' => [
				'required',
				'integer',
				'min:0',
			],
			'total' => [
				'required',
				'integer',
				'min:0',
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
		];
	}
}
