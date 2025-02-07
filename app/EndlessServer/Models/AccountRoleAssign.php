<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountRoleAssign extends Model
{
	protected $table = 'endless_server.account_role_assigns';

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function role(): BelongsTo
	{
		return $this->belongsTo(Role::class);
	}
}