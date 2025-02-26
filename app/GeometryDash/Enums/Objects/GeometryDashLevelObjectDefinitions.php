<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashLevelObjectDefinitions: int
{
	public const string GLUE = ':';

	case ID = 1;
	case NAME = 2;
	case DESCRIPTION = 3;
	case DATA = 4;
	case VERSION = 5;
	case CREATOR_PLAYER_ID = 6;
	case IS_RATED = 8;
	case DIFFICULTY = 9;
	case DOWNLOADS = 10;
	case AUDIO_TRACK = 12;
	case GAME_VERSION = 13;
	case LIKES = 14;
	case LENGTH = 15;
	case IS_DEMON = 17;
	case STARS = 18;
	case FEATURED_SCORE = 19;
	case IS_AUTO = 25;
	case PASSWORD = 27;
	case CREATED_AT = 28;
	case UPDATED_AT = 29;
	case ORIGINAL_LEVEL_ID = 30;
	case IS_TWO_PLAYER = 31;
	case SONG_ID = 35;
	case COINS = 37;
	case IS_COIN_VERIFIED = 38;
	case REQUESTED_STARS = 39;
	case IS_LDM = 40;
	case SPECIAL_ID = 41;
	case EPIC_TYPE = 42;
	case DEMON_DIFFICULTY = 43;
	case GAUNTLET_ID = 44;
	case OBJECTS = 45;
	case EDITOR_TIME = 46;
	case PREVIOUS_EDITOR_TIME = 47;

	/** @deprecated */
	case SETTINGS = 48;

	case SONG_IDS = 52;
	case SOUND_EFFECT_IDS = 53;

	/** @deprecated */
	case UNKNOWN_1 = 54;

	case VERIFICATION_TIME = 57;
}