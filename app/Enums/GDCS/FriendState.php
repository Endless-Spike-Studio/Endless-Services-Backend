<?php

namespace App\Enums\GDCS;

enum FriendState: int
{
    case NONE = 0;
    case IS = 1;
    case OUT_COMING = 3;
    case IN_COMING = 4;
}
