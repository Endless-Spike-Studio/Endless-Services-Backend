<?php

namespace App\EndlessServer\Models;

use App\EndlessServer\Notifications\EmailVerificationNotification;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Account extends Model implements MustVerifyEmailContract
{
	use  Notifiable, MustVerifyEmail;

	protected $table = 'endless_server.accounts';

	protected $fillable = ['name', 'email', 'password'];

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

	public function messages(): HasMany
	{
		return $this->hasMany(AccountMessage::class);
	}

	public function receiveMessages(): HasMany
	{
		return $this->hasMany(AccountMessage::class, 'target_account_id');
	}

	public function blocklist(): HasMany
	{
		return $this->hasMany(AccountBlocklist::class);
	}

	public function friendRequests(): HasMany
	{
		return $this->hasMany(AccountFriendRequest::class);
	}

	public function receiveFriendRequests(): HasMany
	{
		return $this->hasMany(AccountFriendRequest::class, 'target_account_id');
	}

	public function friends(): HasManyThrough
	{
		return $this->hasManyThrough(Account::class, AccountFriend::class, secondKey: 'id', secondLocalKey: 'target_account_id');
	}

	public function roles(): HasManyThrough
	{
		return $this->hasManyThrough(Role::class, AccountRoleAssign::class, secondKey: 'id', secondLocalKey: 'role_id');
	}

	protected function casts(): array
	{
		return [
			'password' => 'hashed'
		];
	}
}