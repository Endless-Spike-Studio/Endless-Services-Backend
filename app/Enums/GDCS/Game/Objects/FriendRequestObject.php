<?php

namespace App\Enums\GDCS\Game\Objects;

enum FriendRequestObject: int
{
	public const TARGET_NAME = 1;
	public const TARGET_USER_ID = 2;
	public const TARGET_ICON_ID = 9;
	public const TARGET_COLOR_ID = 10;
	public const TARGET_SECOND_COLOR_ID = 11;
	public const TARGET_ICON_TYPE = 14;
	public const TARGET_SPECIAL = 15;
	public const TARGET_UUID = 16;
	public const ID = 32;
	public const COMMENT = 35;
	public const AGE = 37;
	public const IS_NEW = 41;
}
