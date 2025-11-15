<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Level;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLevelScoreModes;
use App\GeometryDash\Enums\GeometryDashLevelScoreTypes;
use Illuminate\Validation\Rule;

class GameNormalLevelScoreLoadRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			'levelID' => [
				'required',
				'integer',
				Rule::exists(Level::class, 'id')
			],
			'percent' => [
				'required',
				'integer'
			],
			'time' => [
				'required',
				'integer',
				'in:0'
			],
			'points' => [
				'required',
				'integer',
				'in:0'
			],
			'plat' => [
				'required',
				'boolean',
				'in:0'
			],
			'type' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelScoreTypes::class)
			],
			'mode' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelScoreModes::class),
				'in:0'
			],
			's1' => [
				'required',
				'integer'
			],
			's2' => [
				'required',
				'integer'
			],
			's3' => [
				'required',
				'integer'
			],
			's4' => [
				'required',
				'integer'
			],
			's5' => [
				'required',
				'integer'
			],
			's6' => [
				'nullable',
				'string'
			],
			's7' => [
				'required',
				'string'
			],
			's8' => [
				'required',
				'integer'
			],
			's9' => [
				'required',
				'integer'
			],
			's10' => [
				'required',
				'integer'
			],
			'chk' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}
}