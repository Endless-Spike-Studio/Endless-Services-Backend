<?php

namespace App\Enums\GDCS\Game\Algorithm;

enum Keys: int
{
	case MESSAGES = 14521;
	case LEVEL_PASSWORD = 26364;
	case ACCOUNT_PASSWORD = 37526;
	case LEVEL_LEADERBOARD = 39673;
	case LEVEL_SEED = 41274;
	case COMMENT_CHK = 29481;
	case CHALLENGES = 19847;
	case REWARD = 59182;
	case LIKE_AND_RATE = 58281;
	public const LIKE = Keys::LIKE_AND_RATE;
	public const RATE = Keys::LIKE_AND_RATE;
	case USER_PROFILE = 85271;
	case VAULT_CODES = 19283;
	case LOAD_DATA = 48291;
}
