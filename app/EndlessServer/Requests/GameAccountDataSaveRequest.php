<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Support\Facades\Auth;

class GameAccountDataSaveRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(),
			'saveData' => [
				'required',
				'string'
			],
			...$this->secret(GeometryDashSecrets::ACCOUNT)
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}