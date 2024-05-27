<?php

namespace App\Events;

use App\Models\GDCS\Level;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LevelRated
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public function __construct(readonly Level $level)
	{

	}
}
