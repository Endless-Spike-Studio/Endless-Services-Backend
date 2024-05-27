<?php

namespace App\Enums\GDCS\Game\Objects;

enum MessageObject: int
{
	public const ID = 1;
	public const ACCOUNT_ID = 2;
	public const USER_ID = 3;
	public const SUBJECT = 4;
	public const BODY = 5;
	public const USER_NAME = 6;
	public const AGE = 7;
	public const IS_READ = 8;
	public const IS_SENDER = 9;
}
