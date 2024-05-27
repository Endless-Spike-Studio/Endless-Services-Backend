<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContestParticipant extends Model
{
	protected $table = 'gdcs_contest_participants';

	protected $fillable = ['account_id', 'level_id'];

	public function contest(): BelongsTo
	{
		return $this->belongsTo(Contest::class);
	}

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}
