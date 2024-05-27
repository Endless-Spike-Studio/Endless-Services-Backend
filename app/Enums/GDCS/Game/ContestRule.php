<?php

namespace App\Enums\GDCS\Game;

enum ContestRule: string
{
	case EDITABLE = 'editable';
	case UNIQUE_ACCOUNT = 'unique_account';
	case UNIQUE_LEVEL = 'unique_level';
	case UNIQUE_LEVEL_CONTEST = 'unique_level_contest';
}
