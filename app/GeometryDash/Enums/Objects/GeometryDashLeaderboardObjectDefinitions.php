<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashLeaderboardObjectDefinitions: int
{
	public const string SEGMENTATION = '#';
	public const string GLUE = ':';
	public const string SEPARATOR = '|';

	case PLAYER_NAME = 1;
	case PLAYER_ID = 2;

	case PLAYER_STARS_OR_PERCENT = 3;

	public const GeometryDashLeaderboardObjectDefinitions PLAYER_STARS = GeometryDashLeaderboardObjectDefinitions::PLAYER_STARS_OR_PERCENT;
	public const GeometryDashLeaderboardObjectDefinitions PERCENT = GeometryDashLeaderboardObjectDefinitions::PLAYER_STARS_OR_PERCENT;

	case PLAYER_DEMONS = 4;
	case RANKING = 6;
	case PLAYER_CREATOR_POINTS = 8;
	case PLAYER_ICON_ID = 9;
	case PLAYER_COLOR_1 = 10;
	case PLAYER_COLOR_2 = 11;
	case PLAYER_COINS = 13;
	case PLAYER_ICON_TYPE = 14;
	case PLAYER_SPECIAL = 15;
	case PLAYER_UUID = 16;
	case PLAYER_USER_COINS = 17;
	case AGE = 42;
	case PLAYER_DIAMONDS = 46;
	case PLAYER_MOONS = 52;
}