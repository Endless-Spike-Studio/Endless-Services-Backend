<?php

namespace Database\Factories\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function guest(): UserFactory
    {
        $uuid = $this->faker->unique()
            ->uuid();

        return $this->state([
            'uuid' => $uuid,
            'udid' => $uuid
        ]);
    }

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'udid' => Account::factory(),
            'uuid' => $this->faker->unique()
                ->uuid()
        ];
    }
}
