<?php

namespace Database\Factories;

use App\EndlessServer\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
	protected $model = Account::class;

	public function definition(): array
	{
		$faker = $this->faker->unique();

		return [
			'name' => $faker->name,
			'email' => $faker->safeEmail,
			'password' => $faker->password
		];
	}
}
