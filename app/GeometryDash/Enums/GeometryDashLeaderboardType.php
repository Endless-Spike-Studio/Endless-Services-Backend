<?php

namespace App\GeometryDash\Enums;

enum GeometryDashLeaderboardType: string
{
	case TOP = 'top';
	case FRIENDS = 'friends';
	case RELATIVE = 'relative';
	case CREATORS = 'creators';
}