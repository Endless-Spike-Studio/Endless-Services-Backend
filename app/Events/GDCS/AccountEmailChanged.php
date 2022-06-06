<?php

namespace App\Events\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Foundation\Events\Dispatchable;

class AccountEmailChanged
{
    use Dispatchable;

    public function __construct(
        public Account $model
    )
    {

    }
}
