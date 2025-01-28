<?php

namespace App\EndlessServer\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountGjp2Binding extends Model
{
	protected $table = 'endless_server.account_gjp2_bindings';

	protected $fillable = ['gjp2'];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function value(): Attribute
	{
		return new Attribute(
			fn() => $this->gjp2
		);
	}
}