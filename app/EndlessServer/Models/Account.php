<?php

namespace App\EndlessServer\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Model;

class Account extends Model implements MustVerifyEmailContract
{
	use MustVerifyEmail;

	protected $table = 'endless_server.accounts';

	protected function casts(): array
	{
		return [
			'password' => 'hashed'
		];
	}
}