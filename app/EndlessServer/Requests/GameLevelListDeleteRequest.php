<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\LevelList;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLevelListDeleteRequest extends GameRequest
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
				Rule::exists(LevelList::class, 'id')
			],
			...$this->secret(GeometryDashSecrets::LEVEL)
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}