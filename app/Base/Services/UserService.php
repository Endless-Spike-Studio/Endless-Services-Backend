<?php

namespace App\Base\Services;

use App\Base\Models\User;

class UserService
{
	public function register(string $name, string $email, string $password)
	{
		return User::create([
			'name' => $name,
			'email' => $email,
			'password' => $password,
		]);
	}
}