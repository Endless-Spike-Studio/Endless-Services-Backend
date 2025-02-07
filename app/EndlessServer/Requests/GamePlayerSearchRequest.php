<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;

class GamePlayerSearchRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			'str' => [
				'required',
				'string'
			],
			'total' => [
				'nullable',
				'integer'
			],
			'page' => [
				'nullable',
				'integer'
			],
			...$this->secret()
		];
	}
}