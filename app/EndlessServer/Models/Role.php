<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Role extends Model
{
	protected $table = 'endless_server.roles';

	public function permissions(): HasManyThrough
	{
		return $this->hasManyThrough(Permission::class, RolePermissionAssign::class, secondKey: 'id', secondLocalKey: 'role_id');
	}
}