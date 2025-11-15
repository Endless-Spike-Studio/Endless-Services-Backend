<?php

namespace App\GeometryDash\Enums\Objects;

use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageStates;

enum GeometryDashPlayerInfoObjectDefinitions: int
{
	public const string SEGMENTATION = '#';
	public const string GLUE = ':';
	public const string SEPARATOR = '|';

	case PLAYER_NAME = 1;
	case PLAYER_ID = 2;
	case PLAYER_STARS = 3;
	case PLAYER_DEMONS = 4;
	case RANKING = 6;
	case ACCOUNT_HIGHLIGHT = 7;
	case PLAYER_CREATOR_POINTS = 8;
	case PLAYER_ICON_ID = 9;
	case PLAYER_COLOR_1 = 10;
	case PLAYER_COLOR_2 = 11;
	case PLAYER_COINS = 13;
	case PLAYER_ICON_TYPE = 14;
	case PLAYER_SPECIAL = 15;
	case PLAYER_UUID = 16;
	case PLAYER_USER_COINS = 17;

	/** @see GeometryDashAccountSettingMessageStates */
	case ACCOUNT_MESSAGE_STATE = 18;

	/** @see GeometryDashAccountSettingFriendRequestStates */
	case ACCOUNT_FRIEND_REQUEST_STATE = 19;

	case ACCOUNT_YOUTUBE = 20;
	case PLAYER_CUBE_ID = 21;
	case PLAYER_SHIP_IP = 22;
	case PLAYER_BALL_ID = 23;
	case PLAYER_BIRD_ID = 24;
	case PLAYER_WAVE_ID = 25;
	case PLAYER_ROBOT_ID = 26;

	/** @deprecated */
	case PLAYER_STREAK_ID = 27;

	case PLAYER_GLOW_ID = 28;
	case IS_REGISTERED = 29;
	case PLAYER_GLOBAL_RANK = 30;
	case ACCOUNT_FRIEND_STATE = 31;
	case ACCOUNT_IN_COMING_FRIEND_REQUEST_ID = 32;
	case ACCOUNT_IN_COMING_FRIEND_REQUEST_COMMENT = 35;
	case ACCOUNT_IN_COMING_FRIEND_REQUEST_AGE = 37;
	case ACCOUNT_NEW_MESSAGE_COUNT = 38;
	case ACCOUNT_NEW_FRIEND_REQUEST_COUNT = 39;
	case ACCOUNT_NEW_FRIEND_COUNT = 40;
	case ACCOUNT_HAS_NEW_FRIEND_REQUEST = 41;
	case PLAYER_SPIDER_ID = 43;
	case ACCOUNT_TWITTER = 44;
	case ACCOUNT_TWITCH = 45;
	case PLAYER_DIAMONDS = 46;
	case PLAYER_EXPLOSION_ID = 48;
	case ACCOUNT_MOD_LEVEL = 49;

	/** @see GeometryDashAccountSettingCommentHistoryStates */
	case ACCOUNT_COMMENT_HISTORY_STATE = 50;

	case PLAYER_COLOR_3 = 51;
	case PLAYER_MOONS = 52;
	case PLAYER_SWING_ID = 53;
	case PLAYER_JETPACK_ID = 54;
	case PLAYER_COMPLETED_DEMONS_INFO = 55;
	case PLAYER_COMPLETED_CLASSIC_LEVELS_INFO = 56;
	case PLAYER_COMPLETED_PLATFORMER_LEVELS_INFO = 57;
}