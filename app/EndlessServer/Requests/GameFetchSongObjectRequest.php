<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;

class GameFetchSongObjectRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			'songID' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}
}