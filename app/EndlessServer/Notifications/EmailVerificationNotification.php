<?php

namespace App\EndlessServer\Notifications;

use App\EndlessServer\Data\EmailVerificationData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
	use Queueable;

	public function via($notifiable): array
	{
		if (Str::contains($notifiable, '@')) {
			return ['mail'];
		}

		return [];
	}

	public function toMail($notifiable): MailMessage
	{
		$mail = app(MailMessage::class);

		$mail->level = 'success';

		$mail->subject = __('验证电子邮箱地址');

		$mail->greeting = __('您好 :email', [
			'email' => $notifiable['name']
		]);

		$mail->introLines[] = __('您在 :datetime 注册了 :name 账号, 系统需要验证您的邮箱地址是否真实有效, 如无问题请点击下面的 "对" 按钮', [
			'datetime' => Carbon::parse($notifiable['created_at'])->format('Y/m/d H:i:s'),
			'name' => 'Endless Server'
		]);

		$mail->actionText = __('对');

		$data = encrypt(
			app(EmailVerificationData::class, [
				'id' => $notifiable['id'],
				'created_at' => now()
			])
		);

		$mail->actionUrl = url('/#/endless-server/account/verify?_=' . $data);

		$mail->outroLines[] = __('如果您未注册账号, 请忽略该邮件');

		$mail->salutation = ' ';

		return $mail;
	}
}