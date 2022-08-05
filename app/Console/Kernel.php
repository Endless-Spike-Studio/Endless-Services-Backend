<?php

namespace App\Console;

use App\Jobs\GDCS\CalculateCreatorPoints;
use App\Jobs\GDCS\CleanUnusedTempLevelUploadAccess;
use App\Jobs\GDCS\CleanUnverifiedAccount;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(CleanUnverifiedAccount::class)->hourly();
        $schedule->job(CleanUnusedTempLevelUploadAccess::class)->hourly();
        $schedule->job(CalculateCreatorPoints::class)->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
