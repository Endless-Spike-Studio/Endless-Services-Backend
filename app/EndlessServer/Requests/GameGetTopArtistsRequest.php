<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;

class GameGetTopArtistsRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			...$this->pagination(),
			...$this->secret()
		];
	}
}