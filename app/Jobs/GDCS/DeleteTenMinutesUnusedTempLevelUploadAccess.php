<?php

namespace App\Jobs\GDCS;

use App\Models\GDCS\TempLevelUploadAccess;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteTenMinutesUnusedTempLevelUploadAccess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        TempLevelUploadAccess::query()
            ->where('created_at', '<=', now()->subMinutes(10))
            ->delete();
    }
}
