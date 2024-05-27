<?php

namespace App\Notifications\GDCS;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
	use Queueable;

	protected function resetUrl($notifiable)
	{

	}
}
