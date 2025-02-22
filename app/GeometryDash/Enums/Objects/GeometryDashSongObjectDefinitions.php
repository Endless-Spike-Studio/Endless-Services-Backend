<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashSongObjectDefinitions: int
{
	public const string GLUE = '~|~';

	case ID = 1;
	case NAME = 2;
	case ARTIST_ID = 3;
	case ARTIST_NAME = 4;
	case SIZE = 5;
	case VIDEO_ID = 6;
	case YOUTUBE_URL = 7;
	case IS_VERIFIED = 8;
	case PRIORITY = 9;
	case DOWNLOAD_URL = 10;
	case TYPE = 11;
	case EXTRA_ARTIST_IDS = 12;
	case IS_NEW = 13;
	case NEW_TYPE = 14;
	case EXTRA_ARTIST_INFORMATION = 15;
}