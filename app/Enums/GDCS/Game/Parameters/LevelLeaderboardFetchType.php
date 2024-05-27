<?php

namespace App\Enums\GDCS\Game\Parameters;

enum LevelLeaderboardFetchType: int
{
	public const FRIENDS = 0;
	public const TOP = 1;
	public const WEEK = 2;
}
