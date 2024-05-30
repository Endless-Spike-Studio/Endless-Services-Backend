<?php

namespace App\GeometryDashServer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountLink extends Model
{
	protected $table = 'gdcs_account_links';

	protected $fillable = ['server', 'target_name', 'target_account_id', 'target_user_id'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}
}
