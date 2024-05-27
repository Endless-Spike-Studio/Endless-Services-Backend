<?php

namespace App\Console;

use App\Models\GDCS\Account;
use App\Models\GDCS\TempLevelUploadAccess;
use App\Services\Game\AntiCheatService;
use App\Services\Game\LevelRatingService as LevelRatingServiceAlias;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	protected function schedule(Schedule $schedule): void
	{
		$schedule->call(function () {
			Account::query()
				->where('created_at', '<=', now()->subHour())
				->whereNull('email_verified_at')
				->delete();
		})
			->description('清理注册一小时后还未验证邮箱的账号')
			->hourly();

		$schedule->call(function () {
			TempLevelUploadAccess::query()
				->where('created_at', '<=', now()->subMinutes(10))
				->delete();
		})
			->description('清理十分钟未使用的临时关卡上传许可')
			->hourly();

		$schedule->call(function () {
			LevelRatingServiceAlias::reCalculateCreatorPoints();
		})
			->description('重新计算 Creator Points')
			->daily();

		$schedule->call(function () {
			AntiCheatService::run();
		})
			->description('运行反作弊')
			->daily();
	}

	protected function commands(): void
	{
		$this->load(__DIR__ . '/Commands');
		require_once base_path('routes/console.php');
	}
}
