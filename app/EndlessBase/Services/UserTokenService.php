<?php

namespace App\EndlessBase\Services;

use App\EndlessBase\Models\User;
use App\EndlessBase\Models\UserToken;
use DateTime;
use Illuminate\Support\Str;

class UserTokenService
{
	public function create(User $user, string $name, ?DateTime $expires_at = null)
	{
		while (true) {
			$token = Str::random(64);

			$exists = UserToken::query()
				->where('token', $token)
				->exists();

			if ($exists) {
				continue;
			}

			return UserToken::query()
				->create([
					'user_id' => $user->id,
					'name' => $name,
					'token' => $token,
					'expires_at' => $expires_at
				]);
		}
	}
}