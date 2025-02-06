<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLeaderboardType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLeaderboardListRequest extends FormRequest
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
				'string',
				Rule::enum(GeometryDashLeaderboardType::class)
			],
			'count' => [
				'nullable',
				'integer'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		$this->getValidatorInstance();

		$data = $this->validated();

		if (in_array($data['type'], [GeometryDashLeaderboardType::FRIENDS->value, GeometryDashLeaderboardType::RELATIVE->value], true)) {
			return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
		}

		return true;
	}
}