<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\Game\AccountSettingCommentHistoryState;
use App\Enums\GDCS\Game\AccountSettingFriendRequestState;
use App\Enums\GDCS\Game\AccountSettingMessageState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountSetting extends Model
{
	protected $table = 'gdcs_account_settings';
	protected $fillable = ['message_state', 'friend_request_state', 'comment_history_state', 'youtube_channel', 'twitter', 'twitch',];

	protected $casts = [
		'message_state' => AccountSettingMessageState::class,
		'friend_request_state' => AccountSettingFriendRequestState::class,
		'comment_history_state' => AccountSettingCommentHistoryState::class,
	];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}
}
