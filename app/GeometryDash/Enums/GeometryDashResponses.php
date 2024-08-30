<?php

namespace App\GeometryDash\Enums;

enum GeometryDashResponses: int
{
	case __1 = 1;

	case _1 = -1;
	case _2 = -2;
	case _11 = -11;
	case _12 = -12;

	public const GeometryDashResponses SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses FAILED = GeometryDashResponses::_2;
	public const GeometryDashResponses ACCOUNT_REGISTER_SUCCESS = GeometryDashResponses::__1;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_ACCOUNT_NOT_FOUND = GeometryDashResponses::_11;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_EMAIL_NOT_VERIFIED = GeometryDashResponses::_12;
	public const GeometryDashResponses SONG_DISABLED = GeometryDashResponses::_2;
}