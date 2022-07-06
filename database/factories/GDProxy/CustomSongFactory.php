<?php

namespace Database\Factories\GDProxy;

use App\Models\GDProxy\CustomSong;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomSongFactory extends Factory
{
    protected $model = CustomSong::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker
                ->unique()
                ->name(),
            'artist_name' => $this->faker
                ->unique()
                ->name(),
            'size' => $this->faker
                ->randomFloat(),
            'download_url' => $this->faker
                ->unique()
                ->url(),
        ];
    }
}
