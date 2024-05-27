<?php

namespace App\Events;

use App\Models\GDCS\Account;
use Illuminate\Foundation\Events\Dispatchable;

class AccountRegistered
{
	use Dispatchable;

	public function __construct(readonly Account $account)
	{

	}
}
