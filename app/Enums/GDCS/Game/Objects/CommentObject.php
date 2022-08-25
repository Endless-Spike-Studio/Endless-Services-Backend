<?php

namespace App\Enums\GDCS\Game\Objects;

enum CommentObject: int
{
    public const LEVEL_ID = 1;
    public const CONTENT = 2;
    public const USER_ID = 3;
    public const LIKES = 4;

    /** @deprecated */
    public const DISLIKES = 5;

    public const ID = 6;
    public const IS_SPAM = 7;
    public const ACCOUNT_ID = 8;
    public const AGE = 9;
    public const PERCENT = 10;
    public const MOD_BADGE = 11;
    public const COLOR = 12;
}
