<?php

namespace App\EndlessBase\Services;

use App\Api\Responses\FailedResponse;
use App\EndlessBase\Enums\EndlessServicesAuthenticationGuards;
use App\EndlessBase\Models\User;
use Illuminate\Support\Facades\Auth;

readonly class UserService
{
	public function __construct(
		protected UserTokenService $tokenService
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
		Auth::guard(EndlessServicesAuthenticationGuards::USER->value)
			->attempt([
				'name' => $name,
				'password' => $password
			]);

		return $this->tokenService->create($user, 'api:')->only(['token', 'expires_at']);
	}
}