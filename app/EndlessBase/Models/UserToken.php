<?php

namespace App\EndlessBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserToken extends Model
{
	protected $table = 'public.user_tokens';
	protected $fillable = ['user_id', 'name', 'token', 'expires_at'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}