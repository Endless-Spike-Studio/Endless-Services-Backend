<?php

namespace Database\Factories\GDProxy;

use App\Models\GDProxy\CustomSong;
use App\Models\GDProxy\LevelSongReplace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelSongReplaceFactory extends Factory
{
    protected $model = LevelSongReplace::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'level_id' => 63996127, // cold sweat #21
            'song_id' => CustomSong::factory(),
            'offset' => $this->faker->randomFloat(),
        ];
    }
}
