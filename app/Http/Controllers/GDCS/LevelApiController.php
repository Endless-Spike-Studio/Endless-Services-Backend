<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LevelRateApiRequest;
use App\Http\Requests\GDCS\LevelUpdateApiRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use App\Models\GDCS\DailyLevel;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelRating;
use App\Models\GDCS\WeeklyLevel;
use Base64Url\Base64Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class LevelApiController extends Controller
{
    use HasMessage;

    public function rate(Level $level, LevelRateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        LevelRating::updateOrCreate([
            'level_id' => $level->id
        ], $data);

        $this->pushSuccessMessage(
            __('messages.rate_success')
        );

        return back();
    }

    public function update(Level $level, LevelUpdateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var Account $account */
        $account = $request->user('gdcs');

        if ($level->user_id === $account->user->id) {
            if (!empty($data['desc'])) {
                $data['desc'] = Base64Url::encode($data['desc'], true);
            }

            $level->update($data);
        } else {
            abort(403);
        }

        $this->pushSuccessMessage(
            __('messages.update_success')
        );

        return back();
    }

    public function markAsDaily(Level $level): RedirectResponse
    {
        DailyLevel::firstOrCreate([
            'level_id' => $level->id,
            'apply_at' => DailyLevel::latest()
                    ->value('apply_at')
                ?? Carbon::parse('tomorrow')
        ]);

        $this->pushSuccessMessage(
            __('messages.add_success')
        );

        return back();
    }

    public function markAsWeekly(Level $level): RedirectResponse
    {
        WeeklyLevel::firstOrCreate([
            'level_id' => $level->id,
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
