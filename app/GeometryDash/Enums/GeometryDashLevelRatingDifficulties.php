<?php

namespace App\GeometryDash\Enums;

enum GeometryDashLevelRatingDifficulties: int
{
	case NA = 0;
	case EASY = 10;
	case NORMAL = 20;
	case HARD = 30;
	case HARDER = 40;
	case INSANE = 50;
	case AUTO_OR_DEMON = 60;

	public const GeometryDashLevelRatingDifficulties AUTO = GeometryDashLevelRatingDifficulties::AUTO_OR_DEMON;
	public const GeometryDashLevelRatingDifficulties DEMON = GeometryDashLevelRatingDifficulties::AUTO_OR_DEMON;
}