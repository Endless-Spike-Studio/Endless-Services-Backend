<?php

namespace App\Base\Extensions\Auth\Guards;

use App\Api\Requests\ApiRequest;
use App\Base\Extensions\Auth\Providers\EndlessUserProvider;
use App\Base\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use SensitiveParameter;

class EndlessUserGuard implements Guard
{
	protected ?User $user = null;

	public function __construct(
		protected ApiRequest          $request,
		protected EndlessUserProvider $userProvider
	)
	{

	}

	public function guest(): bool
	{
		return $this->user() === null;
	}

	public function user(): ?User
	{
		if (!$this->hasUser()) {
			$token = $this->request->bearerToken();

			if (!empty($token)) {
				$this->user = $this->userProvider->retrieveByToken(null, $token);
			}
		}

		return $this->user;
	}

	public function hasUser(): bool
	{
		return $this->user !== null;
	}

	public function id()
	{
		$user = $this->user();

		if (!$this->hasUser()) {
			return null;
		}

		return $user->id;
	}

	public function validate(#[SensitiveParameter] array $credentials = []): bool
	{
		return $this->userProvider->validateCredentials($this->userProvider->retrieveByCredentials($credentials), $credentials);
	}

	public function check(): bool
	{
		return $this->user() !== null;
	}

	public function setUser(Authenticatable $user): static
	{
		if ($user instanceof User) {
			$this->user = $user;
		}

		return $this;
	}
}