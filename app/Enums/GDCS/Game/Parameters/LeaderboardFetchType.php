<?php

namespace App\Enums\GDCS\Game\Parameters;

enum LeaderboardFetchType: string
{
    public const TOP = 'top';
    public const FRIENDS = 'friends';
    public const RELATIVE = 'relative';
    public const CREATORS = 'creators';
}
