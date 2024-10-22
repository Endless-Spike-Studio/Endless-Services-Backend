<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryState;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestState;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageState;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

class GameAccountSettingUpdateRequest extends GameRequest
{
	public function rules(): array
	{
		return [
			'accountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			'gjp2' => [
				'required',
				'string'
			],
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
				'required',
				'string'
			],
			'twitter' => [
				'required',
				'string'
			],
			'twitch' => [
				'required',
				'string'
			],
			'secret' => [
				'required',
				'string',
				Rule::in([
					GeometryDashSecrets::ACCOUNT->value
				])
			]
		];
	}
}
