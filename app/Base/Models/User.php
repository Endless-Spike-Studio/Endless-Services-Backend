<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = 'public.users';
	protected $fillable = ['name', 'email', 'password'];

	protected function casts(): array
	{
		return [
			'password' => 'hashed'
		];
	}
}