<?php

namespace App\Notifications;

use App\Models\User;
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
        /** @var User $notifiable */
        $hash = implode('|', [
            $notifiable->getKey(),
            $notifiable->getEmailForVerification(),
        ]);

        return URL::signedRoute('verification.verify', [
            '_' => Crypt::encryptString($hash),
        ]);
    }
}
