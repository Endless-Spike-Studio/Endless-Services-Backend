<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;

trait GameLog
{
    public function logGame(string $message, array $context = []): void
    {
        Log::channel('gdcn')
            ->info($this->formatGameLogMessage($message), $context);
    }

    protected function formatGameLogMessage(string $message): string
    {
        return 'GDCS 游戏日志: ' . $message;
    }
}
