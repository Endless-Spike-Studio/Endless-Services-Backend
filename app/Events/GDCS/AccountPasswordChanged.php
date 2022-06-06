<?php

namespace App\Events\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Foundation\Events\Dispatchable;

class AccountPasswordChanged
{
    use Dispatchable;

    public function __construct(
        public Account $model
    )
    {

    }
}
