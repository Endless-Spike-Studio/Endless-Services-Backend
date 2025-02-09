<?php

namespace App\GeometryDash\Enums;

enum GeometryDashResponses: int
{
	case __1 = 1;

	case _1 = -1;
	case _2 = -2;
	case _3 = -3;
	case _6 = -6;
	case _11 = -11;
	case _12 = -12;

	public const GeometryDashResponses SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses FAILED = GeometryDashResponses::_1;
	public const GeometryDashResponses REQUEST_VALIDATION_FAILED = GeometryDashResponses::_1;
	public const GeometryDashResponses REQUEST_AUTHORIZATION_FAILED = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_REGISTER_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_REGISTER_FAILED_NAME_ALREADY_EXISTS = GeometryDashResponses::_2;
	public const GeometryDashResponses ACCOUNT_REGISTER_FAILED_EMAIL_ALREADY_EXISTS = GeometryDashResponses::_3;
	public const GeometryDashResponses ACCOUNT_REGISTER_FAILED_EMAIL_IS_INVALID = GeometryDashResponses::_6;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_ACCOUNT_NOT_FOUND = GeometryDashResponses::_11;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_WRONG_PASSWORD = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_EMAIL_NOT_VERIFIED = GeometryDashResponses::_12;
	public const GeometryDashResponses PLAYER_DATA_UPDATE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses SONG_DISABLED = GeometryDashResponses::_2;
	public const GeometryDashResponses ACCOUNT_SETTING_UPDATE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_COMMENT_UPLOAD_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_COMMENT_DELETE_FAILED_NOT_FOUND = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_COMMENT_DELETE_FAILED_NOT_OWNER = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_COMMENT_DELETE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_DATA_SAVE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses REWARD_GET_FAILED_INVALID_TYPE = GeometryDashResponses::_1;
	public const GeometryDashResponses REQUEST_ACCOUNT_ACCESS_FAILED_NO_ROLES = GeometryDashResponses::_1;
	public const GeometryDashResponses REQUEST_ACCOUNT_ACCESS_FAILED_NO_MOD_ROLES = GeometryDashResponses::_1;
	public const GeometryDashResponses PLAYER_SEARCH_FAILED_EMPTY = GeometryDashResponses::_2;
	public const GeometryDashResponses ACCOUNT_MESSAGE_SEND_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_MESSAGE_LIST_FAILED_EMPTY = GeometryDashResponses::_2;
	public const GeometryDashResponses ACCOUNT_MESSAGE_DOWNLOAD_FAILED_NOT_FOUND = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_MESSAGE_DELETE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_BLOCKLIST_ADD_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_BLOCKLIST_DELETE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses PLAYER_INFO_FETCH_FAILED_BLOCKED = GeometryDashResponses::_1;
	public const GeometryDashResponses PLAYER_LIST_FAILED_INVALID_TYPE = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_FRIEND_REQUEST_SEND_FAILED_BLOCKED = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_FRIEND_REQUEST_SEND_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_FRIEND_REQUEST_LIST_EMPTY = GeometryDashResponses::_2;
	public const GeometryDashResponses ACCOUNT_FRIEND_REQUEST_DELETE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_FRIEND_REQUEST_READ_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_FRIEND_REQUEST_ACCEPT_FAILED_NOT_FOUND = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_FRIEND_REQUEST_ACCEPT_SUCCESS = GeometryDashResponses::__1;
}