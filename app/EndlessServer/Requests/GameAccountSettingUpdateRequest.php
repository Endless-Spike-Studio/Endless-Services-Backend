<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageStates;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameAccountSettingUpdateRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->auth_gjp2(),
			'mS' => [
				'required',
				'integer',
				Rule::enum(GeometryDashAccountSettingMessageStates::class)
			],
			'frS' => [
				'required',
				'integer',
				Rule::enum(GeometryDashAccountSettingFriendRequestStates::class)
			],
			'cS' => [
				'required',
				'integer',
				Rule::enum(GeometryDashAccountSettingCommentHistoryStates::class)
			],
			'yt' => [
				'nullable',
				'string'
			],
			'twitter' => [
				'nullable',
				'string'
			],
			'twitch' => [
				'nullable',
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