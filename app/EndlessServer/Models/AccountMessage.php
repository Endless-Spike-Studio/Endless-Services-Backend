<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMessage extends Model
{
	protected $table = 'endless_server.account_messages';

	protected $fillable = ['target_account_id', 'subject', 'body', 'new'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function targetAccount(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'target_account_id');
	}

	protected function casts(): array
	{
		return [
			'new' => 'boolean'
		];
	}
}