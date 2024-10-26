<?php

namespace App\EndlessServer\Models;

use Database\Factories\AccountFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model implements MustVerifyEmailContract
{
	use HasFactory, MustVerifyEmail;

	protected $table = 'endless_server.accounts';

	protected $fillable = ['name', 'email', 'password'];

	protected static function newFactory(): AccountFactory
	{
		return AccountFactory::new();
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
		return $this->hasOne(AccountSetting::class)->withDefault();
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