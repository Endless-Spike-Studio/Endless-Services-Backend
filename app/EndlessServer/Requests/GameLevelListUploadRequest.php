<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\LevelList;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLevelListDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelListUnlistedTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLevelListUploadRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(),
			'listID' => [
				'required',
				'integer',
				'exclude_if:listID,0',
				Rule::exists(LevelList::class, 'id')
			],
			'listName' => [
				'required',
				'string'
			],
			'listDesc' => [
				'nullable',
				'string'
			],
			'listLevels' => [
				'required',
				'string'
			],
			'difficulty' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelListDifficulties::class)
			],
			'listVersion' => [
				'required',
				'integer',
				'exclude_if:listVersion,0'
			],
			'original' => [
				'required',
				'integer',
				'exclude_if:original,0',
				Rule::exists(LevelList::class, 'id')
			],
			'unlisted' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelListUnlistedTypes::class)
			],
			'seed' => [
				'required',
				'string'
			],
			'seed2' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}