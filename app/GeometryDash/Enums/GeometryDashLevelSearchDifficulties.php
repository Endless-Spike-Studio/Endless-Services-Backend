<?php

namespace App\GeometryDash\Enums;

enum GeometryDashLevelSearchDifficulties: int
{
	case NA = -1;
	case DEMON = -2;
	case AUTO = -3;

	case EASY = 1;
	case NORMAL = 2;
	case HARD = 3;
	case HARDER = 4;
	case INSANE = 5;
}