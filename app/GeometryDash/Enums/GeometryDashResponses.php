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
	public const GeometryDashResponses FAILED = GeometryDashResponses::_2;
	public const GeometryDashResponses ACCOUNT_REGISTER_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_REGISTER_FAILED_USERNAME_ALREADY_EXISTS = GeometryDashResponses::_2;
	public const GeometryDashResponses ACCOUNT_REGISTER_FAILED_EMAIL_ALREADY_EXISTS = GeometryDashResponses::_3;
	public const GeometryDashResponses ACCOUNT_REGISTER_FAILED_EMAIL_IS_INVALID = GeometryDashResponses::_6;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_ACCOUNT_NOT_FOUND = GeometryDashResponses::_11;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_WRONG_PASSWORD = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_EMAIL_NOT_VERIFIED = GeometryDashResponses::_12;
	public const GeometryDashResponses PLAYER_DATA_UPDATE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses PLAYER_DATA_UPDATE_FAILED_INVALID_SINFO = GeometryDashResponses::_1;
	public const GeometryDashResponses SONG_DISABLED = GeometryDashResponses::_2;
	public const GeometryDashResponses ITEM_RESTORE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses PLAYER_INFO_FETCH_FAILED_NOT_FOUND = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_SETTING_UPDATE_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_COMMENT_UPLOAD_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_COMMENT_DELETE_FAILED_NOT_FOUND = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_COMMENT_DELETE_FAILED_NOT_OWNER = GeometryDashResponses::_1;
	public const GeometryDashResponses ACCOUNT_COMMENT_DELETE_SUCCESS = GeometryDashResponses::__1;
}