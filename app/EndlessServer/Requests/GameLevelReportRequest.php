<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Level;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Validation\Rule;

class GameLevelReportRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			'levelID' => [
				'required',
				'integer',
				Rule::exists(Level::class, 'id')
			],
			...$this->secret()
		];
	}
}