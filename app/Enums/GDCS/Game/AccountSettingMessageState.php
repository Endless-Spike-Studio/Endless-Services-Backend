<?php

namespace App\Enums\GDCS\Game;

enum AccountSettingMessageState: int
{
    case ANY = 0;
    case FRIENDS = 1;
    case NONE = 2;
}
