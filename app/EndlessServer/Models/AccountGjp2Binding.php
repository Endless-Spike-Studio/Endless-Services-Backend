<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountGjp2Binding extends Model
{
	protected $table = 'endless_server.account_gjp2_bindings';

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}
}