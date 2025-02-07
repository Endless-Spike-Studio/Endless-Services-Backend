<?php

namespace App\EndlessServer\Models;

use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountSetting extends Model
{
	protected $table = 'endless_server.account_settings';

	protected $fillable = ['account_id', 'message_state', 'friend_request_state', 'comment_history_state', 'youtube', 'twitch', 'twitter'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	protected function casts(): array
	{
		return [
			'message_state' => GeometryDashAccountSettingMessageStates::class,
			'friend_request_state' => GeometryDashAccountSettingFriendRequestStates::class,
			'comment_history_state' => GeometryDashAccountSettingCommentHistoryStates::class
		];
	}
}