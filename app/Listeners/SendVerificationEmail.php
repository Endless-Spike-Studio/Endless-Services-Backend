<?php

namespace App\Listeners;

use App\Events\GDCS\AccountRegistered;
use App\Events\UserRegistered;

class SendVerificationEmail
{
    public function handle(AccountRegistered|UserRegistered $event): void
    {
        if (!$event->model->hasVerifiedEmail()) {
            $event->model->sendEmailVerificationNotification();
        }
    }
}
