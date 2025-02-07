<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountComment extends Model
{
	protected $table = 'endless_server.account_comments';

	protected $fillable = ['content', 'spam'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	protected function casts(): array
	{
		return [
			'spam' => 'boolean'
		];
	}
}