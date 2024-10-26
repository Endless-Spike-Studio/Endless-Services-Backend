<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;

class GameItemRestoreRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			'udid' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}
}