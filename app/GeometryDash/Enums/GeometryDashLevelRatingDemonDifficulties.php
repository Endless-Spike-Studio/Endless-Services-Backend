<?php

namespace App\GeometryDash\Enums;

enum GeometryDashLevelRatingDemonDifficulties: int
{
	case UNKNOWN = 1;

	case EASY = 3;
	case MEDIUM = 4;
	case HARD = 0;
	case INSANE = 5;
	case EXTREME = 6;
}