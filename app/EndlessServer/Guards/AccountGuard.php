<?php

namespace App\EndlessServer\Guards;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountGjp2Binding;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AccountGuard implements Guard
{
	protected ?Account $account = null;

	public function id(): ?int
	{
		if ($this->guest()) {
			return null;
		}

		return $this->account->id;
	}

	public function guest(): bool
	{
		return $this->user() === null;
	}

	public function user(): ?Account
	{
		if ($this->account === null) {
			if (Request::filled(['userName', 'password'])) {
				if ($this->tryUserNameAndPassword()) {
					return $this->user();
				}
			}

			if (Request::filled(['accountID', 'gjp2'])) {
				if ($this->tryAccountIdAndGjp2()) {
					return $this->user();
				}
			}

			return null;
		}

		return $this->account;

	}

	protected function tryUserNameAndPassword(): bool
	{
		$userName = Request::string('userName');
		$password = Request::string('password');

		$account = Account::query()
			->where('name', $userName)
			->first();

		if ($account === null) {
			return false;
		}

		if (!Hash::check($password, $account->password)) {
			return false;
		}

		$this->account = $account;
		return true;
	}

	public function check(): bool
	{
		return !$this->guest();
	}

	protected function tryAccountIdAndGjp2(): bool
	{
		$accountID = Request::string('accountID');
		$gjp2 = Request::string('gjp2');

		$binding = AccountGjp2Binding::query()
			->where('account_id', $accountID)
			->where('gjp2', $gjp2)
			->first();

		if ($binding === null) {
			return false;
		}

		$this->account = $binding->account;

		return true;
	}

	public function validate(array $credentials = []): false
	{
		return false;
	}

	public function hasUser(): bool
	{
		return !$this->guest();
	}

	public function setUser(Authenticatable $user): AccountGuard
	{
		return $this;
	}
}