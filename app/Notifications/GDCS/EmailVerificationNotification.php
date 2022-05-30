<?php

namespace App\Notifications\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class EmailVerificationNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    protected function verificationUrl($notifiable): string
    {
        /** @var Account $notifiable */

        $hash = implode('|', [
            $notifiable->getkey(),
            $notifiable->getEmailForVerification()
        ]);

        return URL::signedRoute('gdcs.account.verify', [
            '_' => Crypt::encryptString($hash)
        ]);
    }
}
