<?php

namespace App\Services\Game;

use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\LevelRatingDifficulty;

class LevelRatingService
{
    public static function guessDifficultyFromStars(int $stars): LevelRatingDifficulty
    {
        return match ($stars) {
            1, => LevelRatingDifficulty::AUTO,
            2 => LevelRatingDifficulty::EASY,
            3 => LevelRatingDifficulty::NORMAL,
            4, 5 => LevelRatingDifficulty::HARD,
            6, 7 => LevelRatingDifficulty::HARDER,
            8, 9 => LevelRatingDifficulty::INSANE,
            10 => LevelRatingDifficulty::DEMON,
            default => LevelRatingDifficulty::NA
        };
    }

    public static function guessDemonDifficultyFromRating(int $rating): LevelRatingDemonDifficulty
    {
        return match ($rating) {
            1 => LevelRatingDemonDifficulty::EASY,
            2 => LevelRatingDemonDifficulty::MEDIUM,
            4 => LevelRatingDemonDifficulty::INSANE,
            5 => LevelRatingDemonDifficulty::EXTREME,
            default => LevelRatingDemonDifficulty::HARD
        };
    }
}
