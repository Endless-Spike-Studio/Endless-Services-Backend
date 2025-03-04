<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLevelDeleteRequest extends GameRequest
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
			...$this->secret(GeometryDashSecrets::LEVEL)
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->check();
	}
}