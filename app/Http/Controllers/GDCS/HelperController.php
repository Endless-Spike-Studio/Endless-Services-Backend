<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\LevelRatingDifficulty;
use App\Http\Controllers\Controller;

class HelperController extends Controller
{
    public static function guessLevelRatingDemonDifficultyFromName(string $name): LevelRatingDemonDifficulty
    {
        return match ($name) {
            'easy' => LevelRatingDemonDifficulty::EASY,
            'medium' => LevelRatingDemonDifficulty::MEDIUM,
            default => LevelRatingDemonDifficulty::HARD,
            'insane' => LevelRatingDemonDifficulty::INSANE,
            'extreme' => LevelRatingDemonDifficulty::EXTREME
        };
    }

    public static function guessLevelRatingDifficultyFromStars(int $stars): LevelRatingDifficulty
    {
        return match ($stars) {
            1 => LevelRatingDifficulty::AUTO,
            2 => LevelRatingDifficulty::EASY,
            3 => LevelRatingDifficulty::NORMAL,
            4, 5 => LevelRatingDifficulty::HARD,
            6, 7 => LevelRatingDifficulty::HARDER,
            8, 9 => LevelRatingDifficulty::INSANE,
            10 => LevelRatingDifficulty::DEMON,
            default => LevelRatingDifficulty::NA
        };
    }

    public static function guessLevelRatingDifficultyFromName(string $name): LevelRatingDifficulty
    {
        return match ($name) {
            'auto' => LevelRatingDifficulty::AUTO,
            'easy' => LevelRatingDifficulty::EASY,
            'normal' => LevelRatingDifficulty::NORMAL,
            'hard' => LevelRatingDifficulty::HARD,
            'harder' => LevelRatingDifficulty::HARDER,
            'insane' => LevelRatingDifficulty::INSANE,
            'demon' => LevelRatingDifficulty::DEMON,
            default => LevelRatingDifficulty::NA
        };
    }
}
