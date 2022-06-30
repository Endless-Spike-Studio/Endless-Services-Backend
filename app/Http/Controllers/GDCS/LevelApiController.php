<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\DailyLevel;
use App\Models\GDCS\WeeklyLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class LevelApiController extends Controller
{
    use HasMessage;

    public function markAsDaily(int $id): RedirectResponse
    {
        DailyLevel::firstOrCreate([
            'level_id' => $id,
            'apply_at' => DailyLevel::latest()
                    ->value('apply_at')
                ?? Carbon::parse('tomorrow')
        ]);

        $this->pushSuccessMessage(
            __('messages.add_success')
        );

        return back();
    }

    public function markAsWeekly(int $id): RedirectResponse
    {
        WeeklyLevel::firstOrCreate([
            'level_id' => $id,
            'apply_at' => WeeklyLevel::latest()
                    ->value('apply_at')
                ?? Carbon::parse('next monday')
        ]);

        $this->pushSuccessMessage(
            __('messages.add_success')
        );

        return back();
    }
}
