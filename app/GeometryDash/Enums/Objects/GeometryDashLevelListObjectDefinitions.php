<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashLevelListObjectDefinitions: int
{
	public const string GLUE = ':';
	public const string SEPARATOR = '|';

	case ID = 1;
	case NAME = 2;
	case DESCRIPTION = 3;
	case VERSION = 5;
	case DIFFICULTY = 7;
	case DOWNLOADS = 10;
	case LIKES = 11;
	case RATED = 19;
	case UPLOAD_TIMESTAMP = 28;
	case UPDATE_TIMESTAMP = 29;
	case ACCOUNT_ID = 49;
	case USERNAME = 50;
	case LEVEL_IDS = 51;
	case REWARD_DIAMONDS = 55;
	case REWARD_REQUIRE_LEVEL_COUNT = 56;
}