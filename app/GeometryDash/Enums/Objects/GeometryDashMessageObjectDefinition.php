<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashMessageObjectDefinition: int
{
	public const string GLUE = ':';

	case ID = 1;
	case ACCOUNT_ID = 2;
	case PLAYER_ID = 3;
	case SUBJECT = 4;
	case BODY = 5;
	case PLAYER_NAME = 6;
	case AGE = 7;
	case IS_READ = 8;
	case IS_SENDER = 9;
}