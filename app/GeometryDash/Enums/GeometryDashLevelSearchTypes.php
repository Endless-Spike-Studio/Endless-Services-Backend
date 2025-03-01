<?php

namespace App\GeometryDash\Enums;

enum GeometryDashLevelSearchTypes: int
{
	case SEARCH = 0;
	case MOST_DOWNLOADED = 1;
	case MOST_LIKED = 2;
	case TRENDING = 3;
	case RECENT = 4;
	case LIST_PLAYER = 5;
	case FEATURED = 6;
	case MAGIC = 7;
	case MOD_SENT = 8;
	case LEVEL_LIST = 10;
	case AWARDED = 11;
	case FOLLOWED = 12;
	case FRIENDS = 13;
	case WORLD_MOST_LIKED = 15;
	case HALL_OF_FAME = 16;
	case WORLD_FEATURED = 17;

	/** @deprecated */
	case UNKNOWN_1 = 18;

	/** @deprecated */
	case UNKNOWN_2 = 19;

	case DAILY_HISTORY = 21;
	case WEEKLY_HISTORY = 22;
	case LIST_LEVELS = 25;

	/** @deprecated */
	case UNKNOWN_3 = 26;
}