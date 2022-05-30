<?php

namespace App\Console;

use App\Jobs\DeleteOneHourUnverifiedUser;
use App\Jobs\GDCS\DeleteOneHourUnverifiedAccount;
use App\Jobs\GDCS\DeleteTenMinutesUnusedTempLevelUploadAccess;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(DeleteOneHourUnverifiedUser::class)->everyTenMinutes();
        $schedule->job(DeleteOneHourUnverifiedAccount::class)->everyTenMinutes();
        $schedule->job(DeleteTenMinutesUnusedTempLevelUploadAccess::class)->everyTenMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
