<?php

namespace App\Jobs\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanUnverifiedAccountJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function handle(): void
	{
		Account::query()
			->where('created_at', '<=', now()->subHour())
			->whereNull('email_verified_at')
			->delete();
	}
}
