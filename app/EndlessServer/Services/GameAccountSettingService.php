<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Models\AccountSetting;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageStates;

readonly class GameAccountSettingService
{
	public function update(int $accountId, GeometryDashAccountSettingMessageStates $messageState, GeometryDashAccountSettingFriendRequestStates $friendRequestState, GeometryDashAccountSettingCommentHistoryStates $commentHistoryState, string $youtube, string $twitter, string $twitch): int
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