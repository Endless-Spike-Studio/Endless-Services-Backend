<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashLeaderboardObjectDefinitions: int
{
	case PLAYER_NAME = 1;
	case PLAYER_ID = 2;
	case PERCENT = 3;
	case RANKING = 6;
	case PLAYER_ICON_ID = 9;
	case PLAYER_COLOR_1 = 10;
	case PLAYER_COLOR_2 = 11;
	case COINS = 13;
	case PLAYER_ICON_TYPE = 14;
	case PLAYER_SPECIAL = 15;
	case PLAYER_UUID = 16;
	case AGE = 42;
}