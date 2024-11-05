<?php

namespace App\Base\Services;

use App\Base\Models\User;
use App\Base\Models\UserToken;
use App\Common\Responses\FailedResponse;
use Illuminate\Support\Facades\Hash;

class UserService
{
	public function __construct(
		protected readonly UserTokenService $tokenService
	)
	{

	}

	public function register(string $name, string $email, string $password)
	{
		return User::query()
			->create([
				'name' => $name,
				'email' => $email,
				'password' => $password,
			]);
	}

	public function login(string $name, string $password): FailedResponse|array
	{
		$user = User::query()
			->where('name', $name)
			->firstOrFail();

		if (!Hash::check($password, $user->password)) {
			return new FailedResponse('登录失败', 401);
		}

		$now = now();
		$token_name = 'api';

		$record = UserToken::query()
			->where('user_id', $user->id)
			->where('name', $token_name)
			->where('expires_at', '>', $now)
			->firstOr(callback: function () use ($now, $user, $token_name) {
				$expires_at = $now->addMinutes(10);
				return $this->tokenService->create($user, $token_name, $expires_at);
			});

		return $record->only(['token', 'expires_at']);
	}
}