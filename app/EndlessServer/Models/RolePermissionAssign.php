<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermissionAssign extends Model
{
	protected $table = 'endless_server.role_permission_assigns';

	public function role(): BelongsTo
	{
		return $this->belongsTo(Role::class);
	}

	public function permission(): BelongsTo
	{
		return $this->belongsTo(Permission::class);
	}
}