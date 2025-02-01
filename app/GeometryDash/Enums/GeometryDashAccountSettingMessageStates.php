<?php

namespace App\GeometryDash\Enums;

enum GeometryDashAccountSettingMessageStates: int
{
	case ALL = 0;
	case FRIENDS_ONLY = 1;
	case NONE = 2;
}