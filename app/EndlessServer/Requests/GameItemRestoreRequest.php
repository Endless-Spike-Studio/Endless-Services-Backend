<?php

namespace App\EndlessServer\Requests;

use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

class GameItemRestoreRequest extends GameRequest
{
	public function rules(): array
	{
		return [
			'udid' => [
				'required',
				'string'
			],
			'secret' => [
				'required',
				'string',
				Rule::in([
					GeometryDashSecrets::COMMON->value
				])
			]
		];
	}
}