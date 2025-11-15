<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashModLevels;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLevelRatingSuggestRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(),
			'levelID' => [
				'required',
				'integer',
				Rule::exists(Level::class)
			],
			'stars' => [
				'required',
				'integer'
			],
			'feature' => [
				'required',
				'integer'
			],
			...$this->secret(GeometryDashSecrets::MOD)
		];
	}

	public function authorize(): bool
	{
		if (!Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check()) {
			return false;
		}

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		return $account->roles()
			->whereNot('mod_level', GeometryDashModLevels::PLAYER->value)
			->exists();
	}
}