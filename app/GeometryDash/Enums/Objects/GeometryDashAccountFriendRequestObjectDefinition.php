<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashAccountFriendRequestObjectDefinition: int
{
	public const string SEGMENTATION = '#';
	public const string GLUE = ':';
	public const string SEPARATOR = '|';

	case TARGET_PLAYER_NAME = 1;
	case TARGET_PLAYER_USER_ID = 2;
	case TARGET_PLAYER_ICON_ID = 9;
	case TARGET_PLAYER_COLOR_1 = 10;
	case TARGET_PLAYER_COLOR_2 = 11;
	case TARGET_PLAYER_ICON_TYPE = 14;
	case TARGET_PLAYER_SPECIAL = 15;
	case TARGET_PLAYER_UUID = 16;
	case ID = 32;
	case COMMENT = 35;
	case AGE = 37;
	case IS_NEW = 41;
}