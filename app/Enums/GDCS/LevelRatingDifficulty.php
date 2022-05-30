<?php

namespace App\Enums\GDCS;

enum LevelRatingDifficulty: int
{
    case NA = 0;
    case EASY = 10;
    case NORMAL = 20;
    case HARD = 30;
    case HARDER = 40;
    case INSANE = 50;
    case DEMON = 60;

    public const AUTO = LevelRatingDifficulty::DEMON;
}
