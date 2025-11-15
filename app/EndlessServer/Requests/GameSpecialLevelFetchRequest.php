<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashSpecialLevelTypes;
use Illuminate\Validation\Rule;

class GameSpecialLevelFetchRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			'type' => [
				'required',
				'integer',
				Rule::enum(GeometryDashSpecialLevelTypes::class)
			],
			'chk' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}
}