<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Support\Facades\Auth;

class GamePlayerDataUpdateRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->auth_gjp2(true),
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
				'nullable',
				'string'
			],
			'dinfow' => [
				'nullable',
				'integer'
			],
			'dinfog' => [
				'nullable',
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
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->check();
	}
}