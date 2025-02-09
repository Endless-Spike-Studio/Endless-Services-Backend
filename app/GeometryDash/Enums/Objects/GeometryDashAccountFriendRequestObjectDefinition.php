<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashAccountFriendRequestObjectDefinition: int
{
	public const string GLUE = ':';

	case TARGET_NAME = 1;
	case TARGET_USER_ID = 2;
	case TARGET_ICON_ID = 9;
	case TARGET_COLOR_ID = 10;
	case TARGET_SECOND_COLOR_ID = 11;
	case TARGET_ICON_TYPE = 14;
	case TARGET_SPECIAL = 15;
	case TARGET_UUID = 16;
	case ID = 32;
	case COMMENT = 35;
	case AGE = 37;
	case IS_NEW = 41;
}