<?php

namespace App\EndlessServer\Traits;

use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashBinaryVersions;
use App\GeometryDash\Enums\GeometryDashGameVersions;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

trait GameRequestRules
{
	protected function versions(?GeometryDashGameVersions $gameVersion = null, ?GeometryDashBinaryVersions $binaryVersion = null): array
	{
		if ($gameVersion === null) {
			$gameVersion = GeometryDashBinaryVersions::LATEST;
		}

		if ($binaryVersion === null) {
			$binaryVersion = GeometryDashBinaryVersions::LATEST;
		}

		return [
			'gameVersion' => [
				'nullable',
				'integer',
				Rule::in([
					$gameVersion->value
				])
			],
			'binaryVersion' => [
				'nullable',
				'integer',
				Rule::in([
					$binaryVersion->value
				])
			]
		];
	}

	protected function gdw(): array
	{
		return [
			'gdw' => [
				'nullable',
				'integer'
			]
		];
	}

	protected function auth_password(): array
	{
		return [
			'userName' => [
				'required',
				'string',
				Rule::exists(Account::class, 'name')
			],
			'password' => [
				'required',
				'string'
			]
		];
	}

	protected function auth_gjp2(): array
	{
		return [
			'accountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			'gjp2' => [
				'required',
				'string'
			]
		];
	}

	protected function secret(?GeometryDashSecrets $secret = null): array
	{
		if ($secret === null) {
			$secret = GeometryDashSecrets::COMMON;
		}

		return [
			'secret' => [
				'required',
				'string',
				Rule::in([
					$secret->value
				])
			]
		];
	}
}