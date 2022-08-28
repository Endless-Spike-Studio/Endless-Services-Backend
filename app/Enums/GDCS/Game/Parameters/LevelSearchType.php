<?php

namespace App\Enums\GDCS\Game\Parameters;

enum LevelSearchType: int
{
    public const SEARCH = 0;
    public const MOST_DOWNLOADED = 1;
    public const MOST_LIKED = 2;
    public const TRENDING = 3;
    public const RECENT = 4;
    public const USER = 5;
    public const FEATURED = 6;
    public const MAGIC = 7;
    public const MOD_SENT = 8;
    public const LIST = 10;
    public const AWARDED = 11;
    public const FOLLOWED = 12;
    public const FRIENDS = 13;
    public const WORLD_MOST_LIKED = 15;
    public const HALL_OF_FAME = 16;
    public const ALSO_FEATURED = 17;
    public const UNKNOWN = 18;
    public const DAILY_HISTORY = 21;
    public const WEEKLY_HISTORY = 22;
}
