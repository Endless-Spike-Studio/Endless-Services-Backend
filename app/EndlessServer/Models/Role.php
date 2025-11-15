<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Role extends Model
{
	protected $table = 'endless_server.roles';

	public function accounts(): HasManyThrough
	{
		return $this->hasManyThrough(Account::class, RolePermissionAssign::class, secondKey: 'id', secondLocalKey: 'account_id');
	}

	public function permissions(): HasManyThrough
	{
		return $this->hasManyThrough(Permission::class, RolePermissionAssign::class, secondKey: 'id', secondLocalKey: 'role_id');
	}
}