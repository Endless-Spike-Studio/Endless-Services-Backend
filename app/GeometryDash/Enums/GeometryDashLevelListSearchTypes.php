<?php

namespace App\GeometryDash\Enums;

enum GeometryDashLevelListSearchTypes: int
{
	case SEARCH = 0;
	case MOST_DOWNLOADED = 1;
	case MOST_LIKED = 2;
	case TRENDING = 3;
	case RECENT = 4;
	case LIST_PLAYER = 5;
	case DEFAULT = 6;
	case MAGIC = 7;
	case AWARDED = 11;
	case FOLLOWED = 12;
	case FRIENDS = 13;
	case MOD_SENT = 27;
}