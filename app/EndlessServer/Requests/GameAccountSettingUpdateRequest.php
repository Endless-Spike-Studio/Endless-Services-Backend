<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryState;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestState;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageState;
use App\GeometryDash\Enums\GeometryDashSecrets;
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
				Rule::enum(GeometryDashAccountSettingMessageState::class)
			],
			'frS' => [
				'required',
				'integer',
				Rule::enum(GeometryDashAccountSettingFriendRequestState::class)
			],
			'cS' => [
				'required',
				'integer',
				Rule::enum(GeometryDashAccountSettingCommentHistoryState::class)
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
}