<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashCommentObjectDefinitions: int
{
	public const string SEGMENTATION = '#';
	public const string GLUE = '~';
	public const string SEPARATOR = '|';

	case LEVEL_ID = 1;
	case CONTENT = 2;
	case PLAYER_ID = 3;
	case LIKES = 4;

	/** @deprecated */
	case DISLIKES = 5;

	case ID = 6;
	case IS_SPAM = 7;
	case PLAYER_UUID = 8;
	case AGE = 9;
	case PERCENT = 10;
	case MOD_BADGE = 11;
	case COLOR = 12;
}