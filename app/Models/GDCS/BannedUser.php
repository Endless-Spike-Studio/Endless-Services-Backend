<?php

namespace App\Models\GDCS;

use App\Enums\Response;
use Illuminate\Database\Eloquent\Model;

class BannedUser extends Model
{
	protected $table = 'gdcs_banned_users';

	protected $fillable = ['user_id', 'login_ban', 'reason', 'comment_ban', 'comment_ban_info', 'expires_at'];

	protected $dates = [
		'expires_at',
	];

	public function getCommentBanInfoAttribute(): int|string|null
	{
		if (!empty($this->comment_ban)) {
			if (empty($this->expires_at)) {
				return Response::COMMENT_CREATE_FAILED_BANNED->value;
			}

			return sprintf('temp_%s_%s', $this->expires_at->diffInSeconds(), $this->reason);
		}

		return null;
	}
}
