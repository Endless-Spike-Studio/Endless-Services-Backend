<?php

namespace App\Enums\GDCS\Game\Objects;

use App\Enums\GDCS\AccountSettingCommentHistoryState;
use App\Enums\GDCS\AccountSettingFriendRequestState;
use App\Enums\GDCS\AccountSettingMessageState;
use App\Enums\GDCS\FriendState;

enum UserObject: int
{
    public const NAME = 1;
    public const ID = 2;
    public const STARS = 3;
    public const DEMONS = 4;
    public const RANKING = 6;

    /** @see https://github.com/gd-programming/gd.docs/blob/main/docs/resources/server/user.md#users */
    public const ACCOUNT_HIGHLIGHT = 7;

    public const CREATOR_POINTS = 8;
    public const ICON_ID = 9;
    public const COLOR_ID = 10;
    public const SECOND_COLOR_ID = 11;

    /** @var int 金币 */
    public const COINS = 13;

    public const ICON_TYPE = 14;
    public const SPECIAL = 15;
    public const UUID = 16;

    /** @var int 银币 */
    public const USER_COINS = 17;

    /** @see AccountSettingMessageState */
    public const MESSAGE_STATE = 18;

    /** @see AccountSettingFriendRequestState */
    public const FRIEND_REQUEST_STATE = 19;

    public const YOUTUBE = 20;
    public const CUBE_ID = 21;
    public const SHIP_IP = 22;
    public const BALL_ID = 23;
    public const BIRD_ID = 24;
    public const WAVE_ID = 25;
    public const ROBOT_ID = 26;
    public const STREAK_ID = 27;
    public const GLOW_ID = 28;
    public const IS_REGISTERED = 29;
    public const GLOBAL_RANK = 30;

    /** @see FriendState */
    public const FRIEND_STATE = 31;

    public const IN_COMING_FRIEND_REQUEST_ID = 32;
    public const IN_COMING_FRIEND_REQUEST_COMMENT = 35;
    public const IN_COMING_FRIEND_REQUEST_AGE = 37;
    public const NEW_MESSAGE_COUNT = 38;
    public const NEW_FRIEND_REQUEST_COUNT = 39;
    public const NEW_FRIEND_COUNT = 40;
    public const IS_NEW_FRIEND_REQUEST = 41;
    public const SPIDER_ID = 43;
    public const TWITTER = 44;
    public const TWITCH = 45;
    public const DIAMONDS = 46;
    public const EXPLOSION_ID = 48;
    public const MOD_LEVEL = 49;

    /** @see AccountSettingCommentHistoryState */
    public const COMMENT_HISTORY_STATE = 50;
}
