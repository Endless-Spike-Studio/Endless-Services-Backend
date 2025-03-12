<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Support\Facades\Auth;

class GameLevelDownloadRequest extends GameRequest
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
				'integer'
			],
			'inc' => [
				'nullable',
				'integer'
			],
			'rs' => [
				'nullable',
				'string'
			],
			'chk' => [
				'nullable',
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