<?php

namespace App\Console;

use App\Jobs\GDCS\CleanUnusedTempLevelUploadAccessJob;
use App\Jobs\GDCS\CleanUnverifiedAccountJob;
use App\Jobs\GDCS\ReCalculateCreatorPointJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(CleanUnverifiedAccountJob::class)->hourly();
        $schedule->job(CleanUnusedTempLevelUploadAccessJob::class)->hourly();
        $schedule->job(ReCalculateCreatorPointJob::class)->daily();
        $schedule->command('backup:run --only-db')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
