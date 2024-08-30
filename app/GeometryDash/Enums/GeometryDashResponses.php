<?php

namespace App\GeometryDash\Enums;

enum GeometryDashResponses: int
{
	case SUCCESS = 1;
	case FAILED = -1;

	case _2 = -2;
	case _12 = -12;

	public const GeometryDashResponses SONG_DISABLED = GeometryDashResponses::_2;

	public const GeometryDashResponses ACCOUNT_REGISTER_SUCCESS = GeometryDashResponses::SUCCESS;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_ACCOUNT_NOT_FOUND = GeometryDashResponses::FAILED;
	public const GeometryDashResponses ACCOUNT_LOGIN_FAILED_EMAIL_NOT_VERIFIED = GeometryDashResponses::_12;
}