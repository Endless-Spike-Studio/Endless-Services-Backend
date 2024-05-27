<?php

namespace App\Enums\GDCS\Game\Algorithm;

enum Salts: string
{
	case LEVEL = 'xI25fpAapCQg';
	case COMMENT = 'xPT6iUrtws0J';
	case LIKE_AND_RATE = 'ysg6pUrtjn0J';
	public const LIKE = Salts::LIKE_AND_RATE;
	public const RATE = Salts::LIKE_AND_RATE;
	case USER_PROFILE = 'xI35fsAapCRg';
	case LEVEL_LEADERBOARD = 'yPg6pUrtWn0J';
	case CHALLENGE = 'oC36fpYaPtdg';
	case REWARD = 'pC26fpYaQCtg';
}
