<?php

namespace App\GeometryDash\Enums;

enum GeometryDashSalts: string
{
	case LEVEL = 'xI25fpAapCQg';
	case COMMENT_CHK = 'xPT6iUrtws0J';
	case LIKE_AND_RATE_CHK = 'ysg6pUrtjn0J';
	case UPDATE_PROFILE_CHK = 'xI35fsAapCRg';
	case LEVEL_LEADERBOARD_CHK = 'yPg6pUrtWn0J';
	case VAULT_OF_SECRETS_AND_CHAMBER_OF_TIME_CODES = 'ask2fpcaqCQ2';
	case CHALLENGES_HASH = 'oC36fpYaPtdg';
	case REWARDS_HASH = 'pC26fpYaQCtg';
	case GJP2 = 'mI29fmAnxgTs';

	public const GeometryDashSalts LIKE_CHK = GeometryDashSalts::LIKE_AND_RATE_CHK;
	public const GeometryDashSalts RATE_CHK = GeometryDashSalts::LIKE_AND_RATE_CHK;
}