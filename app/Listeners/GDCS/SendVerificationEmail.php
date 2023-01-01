<?php

namespace App\Listeners\GDCS;

use App\Events\AccountRegistered;

class SendVerificationEmail
{
    public function handle(AccountRegistered $event): void
    {
        $event->account->sendEmailVerificationNotification();
    }
}
