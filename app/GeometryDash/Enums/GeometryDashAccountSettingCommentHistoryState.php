<?php

namespace App\GeometryDash\Enums;

enum GeometryDashAccountSettingCommentHistoryState: int
{
	case ALL = 0;
	case FRIENDS_ONLY = 1;
	case SELF_ONLY = 2;
}