<?php

namespace App\Enums\GDCS\Game;

enum FriendState: int
{
    case NONE = 0;
    case IS = 1;
    case IN_COMING = 3;
    case OUT_COMING = 4;
}
