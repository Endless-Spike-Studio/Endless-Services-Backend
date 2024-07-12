<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
	protected $table = 'public.users';
	protected $fillable = ['name', 'email', 'password'];

	public function setPasswordAttribute(string $password): void
	{
		$this->attributes['password'] = Hash::make($password);
	}
}