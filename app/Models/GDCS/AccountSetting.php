<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\AccountSettingCommentHistoryState;
use App\Enums\GDCS\AccountSettingFriendRequestState;
use App\Enums\GDCS\AccountSettingMessageState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountSetting extends Model
{
    protected $table = 'gdcs_account_settings';

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
