<?php

namespace App\Enums\GDCS\Game;

enum AccountSettingCommentHistoryState: int
{
	case ANY = 0;
	case FRIENDS = 1;
	case ME = 2;
}
