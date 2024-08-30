<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Player;
use Illuminate\Support\Str;

class GameAccountService
{
	public function register(string $name, string $email, string $password)
	{
		return Account::query()
			->create([
				'name' => $name,
				'email' => $email,
				'password' => $password
			]);
	}

	public function queryAccountPlayer(Account $account, string $udid = null)
	{
		if (empty($udid)) {
			$udid = Str::uuid()
				->toString();
		}

		return Player::query()
			->firstOrCreate([
				'uuid' => $account->id
			], [
				'name' => $account->name,
				'udid' => $udid
			]);
	}
}