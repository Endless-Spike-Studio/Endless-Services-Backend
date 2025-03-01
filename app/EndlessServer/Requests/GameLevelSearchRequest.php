<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelSearchDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelSearchTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLevelSearchRequest extends GameRequest
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
				Rule::enum(GeometryDashLevelSearchTypes::class)
			],
			'str' => [
				'nullable',
				'string'
			],
			'diff' => [
				'required',
				'exclude_if:diff,-',
				'integer',
				Rule::enum(GeometryDashLevelSearchDifficulties::class)
			],
			'len' => [
				'required',
				'exclude_if:len,-',
				'integer',
				Rule::enum(GeometryDashLevelLengths::class)
			],
			'star' => [
				'nullable',
				'boolean'
			],
			'uncompleted' => [
				'nullable',
				'boolean'
			],
			'onlyCompleted' => [
				'nullable',
				'boolean'
			],
			'completedLevels' => [
				'required_if:uncompleted,1',
				'required_if:onlyCompleted,1',
				'string'
			],
			'featured' => [
				'nullable',
				'boolean'
			],
			'original' => [
				'nullable',
				'boolean'
			],
			'twoPlayer' => [
				'nullable',
				'boolean'
			],
			'coins' => [
				'nullable',
				'boolean'
			],
			'song' => [
				'nullable',
				'integer'
			],
			'customSong' => [
				'nullable',
				'integer'
			],
			'noStar' => [
				'nullable',
				'boolean'
			],
			'epic' => [
				'nullable',
				'boolean'
			],
			'legendary' => [
				'nullable',
				'boolean'
			],
			'mythic' => [
				'nullable',
				'boolean'
			],
			'followed' => [
				'required_if:type,' . GeometryDashLevelSearchTypes::FOLLOWED->value,
				'string'
			],
			'local' => [
				'required_if:type,' . GeometryDashLevelSearchTypes::LIST_PLAYER->value,
				'boolean'
			],
			...$this->pagination(),
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->check();
	}
}