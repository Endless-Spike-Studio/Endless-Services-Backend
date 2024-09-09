<?php

namespace App\EndlessServer\Requests;

use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

class GameFetchSongObjectRequest extends GameRequest
{
	public function rules(): array
	{
		return [
			'songID' => [
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