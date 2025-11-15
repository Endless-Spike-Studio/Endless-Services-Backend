<?php

namespace App\GeometryDash\Enums;

enum GeometryDashFriendStates: int
{
	case NONE = 0;
	case ALREADY = 1;
	case SEND = 3;
	case RECEIVED = 4;
}