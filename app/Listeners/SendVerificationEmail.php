<?php

namespace App\Listeners;

use App\Events\GDCS\AccountEmailChanged;
use App\Events\GDCS\AccountRegistered;
use App\Events\UserEmailChanged;
use App\Events\UserRegistered;

class SendVerificationEmail
{
    public function handle(AccountRegistered|UserRegistered|AccountEmailChanged|UserEmailChanged $event): void
    {
        if (!$event->model->hasVerifiedEmail()) {
            $event->model->sendEmailVerificationNotification();
        }
    }
}
