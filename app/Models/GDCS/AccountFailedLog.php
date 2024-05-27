<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountFailedLog extends Model
{
	protected $table = 'gdcs_account_failed_logs';

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}
}
