<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashBinaryVersions;
use App\GeometryDash\Enums\GeometryDashGameVersions;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

class GamePlayerInfoFetchRequest extends GameRequest
{
	public function rules(): array
	{
		return [
			'gameVersion' => [
				'nullable',
				'integer',
				Rule::in([
					GeometryDashGameVersions::LATEST->value
				])
			],
			'binaryVersion' => [
				'nullable',
				'integer',
				Rule::in([
					GeometryDashBinaryVersions::LATEST->value
				])
			],
			'gdw' => [
				'nullable',
				'integer'
			],
			'accountID' => [
				'sometimes',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			'gjp2' => [
				'sometimes',
				'string'
			],
			'targetAccountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id')
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