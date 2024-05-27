<?php

namespace App\Enums\GDCS\Game\Objects;

enum LeaderboardObject: int
{
	public const USER_NAME = 1;
	public const USER_ID = 2;
	public const PERCENT = 3;
	public const RANKING = 6;
	public const USER_ICON_ID = 9;
	public const USER_COLOR_ID = 10;
	public const USER_SECOND_COLOR_ID = 11;
	public const COINS = 13;
	public const USER_ICON_TYPE = 14;
	public const USER_SPECIAL = 15;
	public const USER_UUID = 16;
	public const AGE = 42;
}
