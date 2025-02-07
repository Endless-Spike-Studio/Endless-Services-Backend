<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;

class GameFetchSongObjectRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			'songID' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}
}