<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLevelListDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelListSearchTypes;
use Illuminate\Validation\Rule;

class GameLevelListListRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			'str' => [
				'nullable',
				'string'
			],
			'type' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelListSearchTypes::class)
			],
			'diff' => [
				'required',
				'exclude_if:diff,-',
				'integer',
				Rule::enum(GeometryDashLevelListDifficulties::class)
			],
			'followed' => [
				'required_if:type,' . GeometryDashLevelListSearchTypes::FOLLOWED->value,
			],
			...$this->pagination(),
			...$this->secret()
		];
	}
}