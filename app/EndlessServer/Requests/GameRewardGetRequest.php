<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashRewardTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameRewardGetRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			'rewardType' => [
				'required',
				'integer',
				Rule::enum(GeometryDashRewardTypes::class)
			],
			'chk' => [
				'required',
				'string'
			],
			'r1' => [
				'required',
				'string'
			],
			'r2' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->check();
	}
}