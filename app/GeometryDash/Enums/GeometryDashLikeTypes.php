<?php

namespace App\GeometryDash\Enums;

enum GeometryDashLikeTypes: int
{
	case LEVEL = 1;
	case LEVEL_COMMENT = 2;
	case ACCOUNT_COMMENT = 3;
	case LEVEL_LIST = 4;
}