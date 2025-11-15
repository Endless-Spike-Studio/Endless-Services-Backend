<?php

namespace App\EndlessProxy\Requests;

use App\Api\Requests\ApiRequest;
use App\GeometryDash\Enums\GeometryDashAccountDataTypes;
use Illuminate\Validation\Rule;

class GameFetchAccountServerRequest extends ApiRequest
{
	public function rules(): array
	{
		return [
			'accountID' => [
				'required',
				'integer'
			],
			'type' => [
				'required',
				'integer',
				Rule::enum(GeometryDashAccountDataTypes::class)
			]
		];
	}
}