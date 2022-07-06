<?php

namespace App\Services\GDCS;

use App\Http\Controllers\GDCS\HelperController;
use App\Models\GDCS\Account;
use App\Models\GDCS\DailyLevel;
use App\Models\GDCS\User;
use App\Models\GDCS\WeeklyLevel;
use Base64Url\Base64Url;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class LevelCommentCommandService extends CommandService
{
    public function test(): string
    {
        return __('messages.command.it_worked');
    }

    public function unRate(): string
    {
        if (! $this->account->can('rate-level')) {
            return __('messages.command.permission_denied');
        }

        $this->level
            ->rating()
            ->delete();

        return __('messages.command.execute_success');
    }

    public function rate(): string
    {
        if (! Arr::has($this->parameters, [0])) {
            return __('messages.command.invalid_parameters');
        }

        if (! $this->account->can('rate-level')) {
            return __('messages.command.permission_denied');
        }

        $stars = Arr::get($this->parameters, 0);
        if (! is_int($stars) || $stars < 1 || $stars > 10) {
            return __('messages.command.invalid_parameters');
        }

        $this->level->rate($stars);
        $featuredScore = Arr::get($this->arguments, 'featured-score');
        if (! empty($featuredScore)) {
            $this->level->rating->update(['featured_score' => $featuredScore]);
        }

        $demonDifficulty = Arr::get($this->arguments, 'demon-difficulty');
        if (! empty($demonDifficulty)) {
            $this->level->rating->update([
                'demon_difficulty' => HelperController::guessLevelRatingDemonDifficultyFromName($demonDifficulty),
            ]);
        }

        if (in_array('epic', $this->options, true)) {
            $this->level->rating->update(['epic' => true]);
        }

        if (in_array('silver-coin', $this->options, true)) {
            $this->level->rating->update(['coin_verified' => true]);
        }

        return __('messages.command.execute_success');
    }

    public function set(): string
    {
        if (! Arr::has($this->parameters, [0, 1])) {
            return __('messages.command.invalid_parameters');
        }

        if (! $this->checkLevelOwner()) {
            return __('messages.command.permission_denied');
        }

        $key = Arr::get($this->parameters, 0);
        $value = Arr::get($this->parameters, 1);

        switch ($key) {
            case 'as':
                if (! $this->account->can('mark-level')) {
                    return __('messages.command.permission_denied');
                }

                switch ($value) {
                    case 'daily':
                        $daily = DailyLevel::query()
                            ->latest()
                            ->first();

                        DailyLevel::query()
                            ->create([
                                'level_id' => $this->level->id,
                                'apply_at' => ! $daily ? Carbon::parse('tomorrow') : $daily->apply_at->addWeek(),
                            ]);
                        break;
                    case 'weekly':
                        $weekly = WeeklyLevel::query()
                            ->latest()
                            ->first();

                        WeeklyLevel::query()
                            ->create([
                                'level_id' => $this->level->id,
                                'apply_at' => ! $weekly ? Carbon::parse('next monday') : $weekly->apply_at->addWeek(),
                            ]);
                        break;
                    default:
                        return __('messages.command.invalid_parameters');
                }
                break;
            case 'name':
                $this->level->update(['name' => $value]);
                break;
            case 'desc':
                $this->level->update([
                    'desc' => Base64Url::encode($value, true),
                ]);
                break;
            case 'password':
                switch ($value) {
                    case 'no-copy':
                        $this->level->update(['password' => 0]);
                        break;
                    case 'free-copy':
                        $this->level->update(['password' => 1]);
                        break;
                    case 'random':
                        try {
                            $this->level->update([
                                'password' => random_int(1000, 999999),
                            ]);

                            return __('messages.command.level_password_change_success_with_password', ['password' => $this->level->password]);
                        } catch (Exception) {
                            return __('messages.command.error');
                        }
                    default:
                        $this->level->update(['password' => $value]);
                        break;
                }
                break;
            case 'audio-track':
                $this->level->update([
                    'song_id' => 0,
                    'audio_track' => $value,
                ]);
                break;
            case 'song':
                $this->level->update([
                    'audio_track' => 0,
                    'song_id' => $value,
                ]);
                break;
            case 'difficulty':
                if (! $this->account->can('rate-level')) {
                    return __('messages.command.permission_denied');
                }

                $this->level->rating->update([
                    'difficulty' => HelperController::guessLevelRatingDifficultyFromName($value),
                ]);
                break;
            case 'demon-difficulty':
                if (! $this->account->can('rate-level')) {
                    return __('messages.command.permission_denied');
                }

                $this->level->rating->update([
                    'demon-difficulty' => HelperController::guessLevelRatingDemonDifficultyFromName($value),
                ]);
                break;
            case 'stars':
                if (! $this->account->can('rate-level')) {
                    return __('messages.command.permission_denied');
                }

                if (! is_int($value) || $value < 1 || $value > 10) {
                    return __('messages.command.invalid_parameters');
                }

                $this->level->rating->update([
                    'stars' => $value,
                    'auto' => $value === 1,
                    'demon' => $value === 10,
                ]);
                break;
            case 'featured-score':
                if (! $this->account->can('rate-level')) {
                    return __('messages.command.permission_denied');
                }

                if (! is_int($value)) {
                    return __('messages.command.invalid_parameters');
                }

                $this->level->rating->update(['featured_score' => $value]);
                break;
            case 'epic':
                if (! $this->account->can('rate-level')) {
                    return __('messages.command.permission_denied');
                }

                $this->level->rating->update([
                    'epic' => $this->booleanMapper[$value],
                ]);
                break;
            case 'silver-coin':
                if (! $this->account->can('rate-level')) {
                    return __('messages.command.permission_denied');
                }

                $this->level->rating->update([
                    'coin_verified' => $this->booleanMapper[$value],
                ]);
                break;
            case 'unlisted':
                $this->level->update([
                    'unlisted' => $this->booleanMapper[$value],
                ]);
                break;
            default:
                return __('messages.command.invalid_parameters');
        }

        return __('messages.command.execute_success');
    }

    protected function checkLevelOwner(): bool
    {
        return $this->account->user->id === $this->level->user_id;
    }

    public function transfer_to(): string
    {
        if (! Arr::has($this->parameters, [0, 1])) {
            return __('messages.command.invalid_parameters');
        }

        if (! $this->checkLevelOwner()) {
            return __('messages.command.permission_denied');
        }

        $type = Arr::get($this->parameters, 0);
        $value = Arr::get($this->parameters, 1);

        switch ($type) {
            case 'user':
                $user = User::query()
                    ->whereKey($value)
                    ->orWhere('name', $value)
                    ->first();

                if ($user === null) {
                    return __('messages.command.error');
                }

                $this->level
                    ->update([
                        'user_id' => $user->id,
                    ]);
                break;
            case 'account':
                $account = Account::query()
                    ->whereKey($value)
                    ->orWhere('name', $value)
                    ->load('user')
                    ->first();

                if ($account === null || $account->user === null) {
                    return __('messages.command.error');
                }

                $this->level->update([
                    'user_id' => $account->user->id,
                ]);
                break;
            default:
                return __('messages.command.invalid_parameters');
        }

        return __('messages.command.execute_success');
    }
}
