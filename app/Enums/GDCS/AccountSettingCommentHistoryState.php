<?php

namespace App\Enums\GDCS;

enum AccountSettingCommentHistoryState: int
{
    case ANY = 0;
    case FRIENDS = 1;
    case ME = 2;
}
