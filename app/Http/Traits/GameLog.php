<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

trait GameLog
{
	public function logGame(string $message, array $context = []): void
	{
		$data = array_merge(Request::context(), $context);

		Log::channel('gdcn')
			->info($this->formatGameLogMessage($message), $data);
	}

	protected function formatGameLogMessage(string $message): string
	{
		return 'GDCS 游戏日志: ' . $message;
	}
}
