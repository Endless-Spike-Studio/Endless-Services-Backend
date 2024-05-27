<?php

namespace App\Notifications\GDCS;

use DragonCode\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FindNameNotification extends Notification implements ShouldQueue
{
	public function via($notifiable): array
	{
		return ['mail'];
	}

	public function toMail($notifiable): MailMessage
	{
		return (new MailMessage)
			->line(__('gdcn.notification.lines.find_name'))
			->line(__('gdcn.notification.lines.name_is_with_name', ['name' => $notifiable->name]))
			->line(__('gdcn.notification.lines.if_you_did_not_forget_name_please_ignore_this'));
	}
}
