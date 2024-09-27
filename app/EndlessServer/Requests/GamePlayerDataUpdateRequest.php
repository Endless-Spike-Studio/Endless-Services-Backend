<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashBinaryVersions;
use App\GeometryDash\Enums\GeometryDashGameVersions;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Auth\Access\Response;
use Illuminate\Validation\Rule;

class GamePlayerDataUpdateRequest extends GameRequest
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
				'required',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			'gjp2' => [
				'required',
				'string'
			],
			'userName' => [
				'nullable',
				'string'
			],
			'stars' => [
				'required',
				'integer'
			],
			'moons' => [
				'required',
				'integer'
			],
			'demons' => [
				'required',
				'integer'
			],
			'diamonds' => [
				'required',
				'integer'
			],
			'icon' => [
				'required',
				'integer'
			],
			'iconType' => [
				'required',
				'integer'
			],
			'coins' => [
				'required',
				'integer'
			],
			'userCoins' => [
				'required',
				'integer'
			],
			'color1' => [
				'nullable',
				'integer'
			],
			'color2' => [
				'nullable',
				'integer'
			],
			'color3' => [
				'nullable',
				'integer'
			],
			'accIcon' => [
				'required',
				'integer'
			],
			'accShip' => [
				'required',
				'integer'
			],
			'accBall' => [
				'required',
				'integer'
			],
			'accBird' => [
				'required',
				'integer'
			],
			'accDart' => [
				'required',
				'integer'
			],
			'accRobot' => [
				'required',
				'integer'
			],
			'accGlow' => [
				'required',
				'integer'
			],
			'accSpider' => [
				'required',
				'integer'
			],
			'accExplosion' => [
				'required',
				'integer'
			],
			'accSwing' => [
				'required',
				'integer'
			],
			'accJetpack' => [
				'required',
				'integer'
			],
			'special' => [
				'nullable',
				'integer'
			],
			'dinfo' => [
				'required',
				'string'
			],
			'dinfow' => [
				'required',
				'integer'
			],
			'dinfog' => [
				'required',
				'integer'
			],
			'sinfo' => [
				'required',
				'string'
			],
			'sinfod' => [
				'required',
				'integer'
			],
			'sinfog' => [
				'required',
				'integer'
			],
			'seed' => [
				'nullable',
				'string'
			],
			'seed2' => [
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