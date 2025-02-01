<?php

namespace App\GeometryDash\Enums\Objects;

use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageStates;

enum GeometryDashPlayerInfoObjectDefinitions: int
{
	public const string GLUE = ':';

	case NAME = 1;
	case ID = 2;
	case STARS = 3;
	case DEMONS = 4;
	case RANKING = 6;
	case ACCOUNT_HIGHLIGHT = 7;
	case CREATOR_POINTS = 8;
	case ICON_ID = 9;
	case COLOR_1 = 10;
	case COLOR_2 = 11;
	case COINS = 13;
	case ICON_TYPE = 14;
	case SPECIAL = 15;
	case UUID = 16;
	case USER_COINS = 17;

	/** @see GeometryDashAccountSettingMessageStates */
	case MESSAGE_STATE = 18;

	/** @see GeometryDashAccountSettingFriendRequestStates */
	case FRIEND_REQUEST_STATE = 19;

	case YOUTUBE = 20;
	case CUBE_ID = 21;
	case SHIP_IP = 22;
	case BALL_ID = 23;
	case BIRD_ID = 24;
	case WAVE_ID = 25;
	case ROBOT_ID = 26;
	case STREAK_ID = 27;
	case GLOW_ID = 28;
	case IS_REGISTERED = 29;
	case GLOBAL_RANK = 30;
	case FRIEND_STATE = 31;
	case IN_COMING_FRIEND_REQUEST_ID = 32;
	case IN_COMING_FRIEND_REQUEST_COMMENT = 35;
	case IN_COMING_FRIEND_REQUEST_AGE = 37;
	case NEW_MESSAGE_COUNT = 38;
	case NEW_FRIEND_REQUEST_COUNT = 39;
	case NEW_FRIEND_COUNT = 40;
	case HAS_NEW_FRIEND_REQUEST = 41;
	case SPIDER_ID = 43;
	case TWITTER = 44;
	case TWITCH = 45;
	case DIAMONDS = 46;
	case EXPLOSION_ID = 48;
	case MOD_LEVEL = 49;

	/** @see GeometryDashAccountSettingCommentHistoryStates */
	case COMMENT_HISTORY_STATE = 50;

	case COLOR_3 = 51;
	case MOONS = 52;
	case SWING_ID = 53;
	case JETPACK_ID = 54;
	case COMPLETED_DEMONS_INFO = 55;
	case COMPLETED_CLASSIC_LEVELS_INFO = 56;
	case COMPLETED_PLATFORMER_LEVELS_INFO = 57;
}