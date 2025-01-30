<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{
	protected $table = 'endless_server.account_settings';

	protected $fillable = ['account_id', 'message_state', 'friend_request_state', 'comment_history_state', 'youtube', 'twitch', 'twitter'];
}