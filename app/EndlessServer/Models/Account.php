<?php

namespace App\EndlessServer\Models;

use App\EndlessServer\Notifications\EmailVerificationNotification;
use Database\Factories\AccountFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Account extends Model implements MustVerifyEmailContract
{
	use HasFactory, Notifiable, MustVerifyEmail;

	protected $table = 'endless_server.accounts';

	protected $fillable = ['name', 'email', 'password'];

	protected static function newFactory(): AccountFactory
	{
		return AccountFactory::new();
	}

	public function sendEmailVerificationNotification(): void
	{
		$notification = app(EmailVerificationNotification::class);

		$this->notify($notification);
	}

	public function player(): HasOne
	{
		return $this->hasOne(Player::class, 'uuid');
	}

	public function gjp2(): HasOne
	{
		return $this->hasOne(AccountGjp2Binding::class);
	}

	public function setting(): HasOne
	{
		return $this->hasOne(AccountSetting::class);
	}

	public function comments(): HasMany
	{
		return $this->hasMany(AccountComment::class);
	}

	protected function casts(): array
	{
		return [
			'password' => 'hashed'
		];
	}
}