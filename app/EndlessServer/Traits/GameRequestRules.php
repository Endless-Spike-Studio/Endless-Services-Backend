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
			$gameVersion = GeometryDashGameVersions::LATEST;
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

	protected function pagination(): array
	{
		return [
			'page' => [
				'nullable',
				'integer'
			],
			'count' => [
				'nullable',
				'integer'
			],
			'total' => [
				'nullable',
				'integer'
			]
		];
	}

	protected function identifies(): array
	{
		return [
			'uuid' => [
				'nullable',
				'string'
			],
			'udid' => [
				'nullable',
				'string'
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

	protected function auth_password(bool $optional = false): array
	{
		return [
			'userName' => [
				$optional ? 'nullable' : 'required',
				'string',
				Rule::exists(Account::class, 'name')
			],
			'password' => [
				$optional ? 'nullable' : 'required',
				'string'
			]
		];
	}

	protected function auth_username_gjp2(bool $optional = false): array
	{
		return [
			'userName' => [
				$optional ? 'nullable' : 'required',
				'string',
				Rule::exists(Account::class, 'name')
			],
			'gjp2' => [
				$optional ? 'nullable' : 'required',
				'string'
			]
		];
	}

	protected function auth_gjp2(bool $optional = false): array
	{
		return [
			'accountID' => [
				$optional ? 'nullable' : 'required',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			'gjp2' => [
				$optional ? 'nullable' : 'required',
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