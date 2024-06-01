<?php

namespace App\GeometryDash\Enums;

enum GeometryDashResponses: int
{
	case SUCCESS = 1;
	case FAILED = -1;

	case SONG_DISABLED = -2;
}