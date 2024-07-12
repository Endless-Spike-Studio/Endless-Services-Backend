<?php

namespace App\Base\Services;

use App\Base\Models\User;
use App\Base\Models\UserToken;
use DateTime;
use Illuminate\Support\Str;

class UserTokenService
{
	public function create(User $user, string $name, DateTime $expires_at = null)
	{
		return UserToken::create([
			'user_id' => $user->id,
			'name' => $name,
			'token' => Str::random(64),
			'expires_at' => $expires_at
		]);
	}
}