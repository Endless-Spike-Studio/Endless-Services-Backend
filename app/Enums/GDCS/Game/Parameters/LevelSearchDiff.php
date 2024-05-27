<?php

namespace App\Enums\GDCS\Game\Parameters;

enum LevelSearchDiff: int
{
	public const NA = -1;
	public const DEMON = -2;
	public const AUTO = -3;
	public const EASY_DEMON = 6;
	public const MEDIUM_DEMON = 7;
	public const HARD_DEMON = 8;
	public const INSANE_DEMON = 9;
	public const EXTREME_DEMON = 10;
}
