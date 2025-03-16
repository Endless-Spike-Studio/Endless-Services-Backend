<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLikeTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameItemLikeRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			'itemID' => [
				'required',
				'integer'
			],
			'like' => [
				'nullable',
				'boolean'
			],
			'type' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLikeTypes::class)
			],
			'special' => [
				'required',
				'integer'
			],
			'rs' => [
				'required',
				'string'
			],
			'chk' => [
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