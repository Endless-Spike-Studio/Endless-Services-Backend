<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\Game\LevelTransferType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelTransferRecord extends Model
{
	protected $table = 'gdcs_level_transfer_records';
	protected $fillable = ['server', 'original_level_id', 'level_id'];
	protected $casts = ['type' => LevelTransferType::class];

	public function account(): BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}
