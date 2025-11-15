<?php

namespace App\GeometryDash\Enums;

enum GeometryDashLevelUnlistedTypes: int
{
	case PUBLIC = 0;
	case FRIENDS_ONLY = 1;
	case SELF_ONLY = 2;
}