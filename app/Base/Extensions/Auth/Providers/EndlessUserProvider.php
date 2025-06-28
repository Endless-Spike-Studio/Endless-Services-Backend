<?php

namespace App\Base\Extensions\Auth\Providers;

use App\Base\Models\User;
use App\Base\Models\UserToken;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Hash;
use SensitiveParameter;

class EndlessUserProvider implements UserProvider
{
	public function __construct(
		protected User $model
	)
	{

	}

	public function retrieveById($identifier)
	{
		return $this->model->query()
			->where('id', $identifier)
			->first();
	}

	public function retrieveByToken($identifier, #[SensitiveParameter] $token)
	{
		$now = now();

		$record = UserToken::query()
			->where('token', $token)
			->where(function (Builder $query) use ($now) {
				$query->whereNull('expires_at');
				$query->orWhere('expires_at', '>', $now);
			})
			->first();

		if ($record === null) {
			return null;
		}

		if ($identifier !== null) {
			if ($record->user_id !== $identifier) {
				return null;
			}
		}

		return $record->user;
	}

	public function updateRememberToken(Authenticatable $user, #[SensitiveParameter] $token): void
	{
		$now = now();

		UserToken::query()
			->where('user_id', $user->id)
			->where('token', $token)
			->where('expires_at', '>', $now)
			->update([
				'expires_at' => $now->addWeek()
			]);
	}

	public function retrieveByCredentials(#[SensitiveParameter] array $credentials)
	{
		if (empty($credentials['name'])) {
			return null;
		}

		$user = User::query()
			->where('name', $credentials['name'])
			->first();

		if ($user === null) {
			return null;
		}

		return $user;
	}

	public function validateCredentials(Authenticatable $user, #[SensitiveParameter] array $credentials): bool
	{
		if (empty($credentials['password'])) {
			return false;
		}

		/**
		 * @var User $user
		 */

		return Hash::check($credentials['password'], $user->password);
	}

	public function rehashPasswordIfRequired(Authenticatable $user, #[SensitiveParameter] array $credentials, bool $force = false): void
	{
		if (empty($credentials['password'])) {
			return;
		}

		/**
		 * @var User $user
		 */

		if ($force || Hash::needsRehash($user->password)) {
			$user->password = Hash::make($credentials['password']);
			$user->save();
		}
	}
}