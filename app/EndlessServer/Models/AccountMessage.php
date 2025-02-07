<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Model;

class AccountMessage extends Model
{
	protected $table = 'endless_server.account_messages';

	protected $fillable = ['target_account_id', 'subject', 'body', 'readed'];

	protected function casts(): array
	{
		return [
			'readed' => 'boolean'
		];
	}
}