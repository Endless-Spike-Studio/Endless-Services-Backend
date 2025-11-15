<?php

namespace App\GeometryDash\Enums;

enum GeometryDashMapPackDifficulties: int
{
	case AUTO = 0;
	case EASY = 1;
	case NORMAL = 2;
	case HARD = 3;
	case HARDER = 4;
	case INSANE = 5;
	case HARD_DEMON = 6;
	case EASY_DEMON = 7;
	case MEDIUM_DEMON = 8;
	case INSANE_DEMON = 9;
	case EXTREME_DEMON = 10;
}