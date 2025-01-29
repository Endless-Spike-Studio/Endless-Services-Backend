<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Models\AccountSetting;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryState;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestState;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageState;

readonly class GameAccountSettingService
{
	public function initialize(int $accountId): void
	{
		$exists = AccountSetting::query()
			->where('account_id', $accountId)
			->exists();

		if ($exists) {
			return;
		}

		AccountSetting::query()
			->create([
				'account_id' => $accountId,
				'message_state' => GeometryDashAccountSettingMessageState::ALL->value,
				'friend_request_state' => GeometryDashAccountSettingFriendRequestState::ALL->value,
				'comment_history_state' => GeometryDashAccountSettingCommentHistoryState::ALL->value,
				'youtube' => '',
				'twitter' => '',
				'twitch' => ''
			]);
	}

	public function update(int $accountId, GeometryDashAccountSettingMessageState $messageState, GeometryDashAccountSettingFriendRequestState $friendRequestState, GeometryDashAccountSettingCommentHistoryState $commentHistoryState, string $youtube, string $twitter, string $twitch): int
	{
		return AccountSetting::query()
			->where('account_id', $accountId)
			->update([
				'message_state' => $messageState->value,
				'friend_request_state' => $friendRequestState->value,
				'comment_history_state' => $commentHistoryState->value,
				'youtube' => $youtube,
				'twitter' => $twitter,
				'twitch' => $twitch
			]);
	}
}