<?php

namespace Database\Factories\NGProxy;

use App\Models\NGProxy\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    protected $model = Song::class;

    public function definition(): array
    {
        return [
            'song_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'artist_id' => 8,
            'artist_name' => $this->faker->name(),
            'size' => $this->faker->randomFloat(),
            'disabled' => false,
            'original_download_url' => $this->faker->url(),
        ];
    }
}
