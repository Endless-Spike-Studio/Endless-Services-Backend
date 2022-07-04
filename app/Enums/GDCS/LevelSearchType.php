<?php

namespace App\Enums\GDCS;

enum LevelSearchType: int
{
    case SEARCH = 0;
    case MOST_DOWNLOADED = 1;
    case MOST_LIKED = 2;
    case TRENDING = 3;
    case RECENT = 4;
    case USER = 5;
    case FEATURED = 6;
    case MAGIC = 7;
    case MOD_SENT = 8;
    case LIST = 10;
    case AWARDED = 11;
    case FOLLOWED = 12;
    case FRIENDS = 13;
    case WORLD_MOST_LIKED = 15;
    case HALL_OF_FAME = 16;
    case ALSO_FEATURED = 17;
    case UNKNOWN = 18;
    case DAILY_HISTORY = 21;
    case WEEKLY_HISTORY = 22;
}
