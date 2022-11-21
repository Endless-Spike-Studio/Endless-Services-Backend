<?php

namespace App\Services\GDCS\Game\Command;

use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\LevelRatingDifficulty;
use App\Events\LevelRated;
use App\Models\GDCS\Account;
use App\Models\GDCS\CustomSong;
use App\Models\GDCS\Level;
use App\Services\Game\LevelRatingService;
use App\Services\Storage\GameLevelDataStorageService;
use Illuminate\Support\Str;

class LevelCommentService extends BaseCommandService
{
    protected bool $currentOperatorIsLevelOwner;

    public function __construct(
        protected string  $data,
        protected Account $account,
        protected ?Level  $level = null
    )
    {
        parent::__construct($data, $account, $level);
        $this->currentOperatorIsLevelOwner = $this->level->creator->is($this->account->user);
    }

    protected function test(): string
    {
        return __('gdcn.game.command.worked');
    }

    protected function rate(): string
    {
        if (!$this->account->can('rate-level')) {
            return __('gdcn.game.command.no_permission');
        }

        $rating = $this->level
            ->rating()
            ->firstOrNew();

        if ($this->parameters[1] === 'delete') {
            $rating->delete();
            return __('gdcn.game.command.level_rate_delete_success');
        }

        $stars = (int)($this->arguments['stars'] ?? $this->parameters[1]);
        if (empty($stars) || !is_numeric($stars) || $stars < 1 || $stars > 10) {
            return __('gdcn.game.command.level_rate_failed_invalid_stars_value', [
                'value' => $stars
            ]);
        }

        $rating->stars = $stars;
        foreach ($this->parameters as $parameter) {
            if (!Str::startsWith($parameter, ['+', '-'])) {
                continue;
            }

            if (str_ends_with($parameter, 'feature')) {
                $rating->featured_score = Str::startsWith($parameter, '+');
            }

            if (str_ends_with($parameter, 'epic')) {
                $rating->epic = Str::startsWith($parameter, '+');
            }

            if (str_ends_with($parameter, 'silver_coin')) {
                $rating->coin_verified = Str::startsWith($parameter, '+');
            }
        }

        if (array_key_exists('feature_score', $this->arguments)) {
            $rating->featured_score = $this->arguments['feature_score'];
        }

        if (array_key_exists('face', $this->arguments)) {
            $face = $this->arguments['face'];

            $difficulty = match ($face) {
                'auto' => LevelRatingDifficulty::AUTO,
                'easy' => LevelRatingDifficulty::EASY,
                'normal' => LevelRatingDifficulty::NORMAL,
                'hard' => LevelRatingDifficulty::HARD,
                'harder' => LevelRatingDifficulty::HARDER,
                'insane' => LevelRatingDifficulty::INSANE,
                'demon', 'easy_demon', 'medium_demon', 'hard_demon', 'insane_demon', 'extreme_demon' => LevelRatingDifficulty::DEMON,
                default => LevelRatingDifficulty::NA
            };

            if ($difficulty === LevelRatingDifficulty::NA) {
                return __('gdcn.game.command.level_rate_failed_invalid_face_value', [
                    'value' => $face
                ]);
            }

            $rating->difficulty = $difficulty;

            switch ($face) {
                case 'auto':
                    $rating->auto = true;
                    $rating->demon = false;
                    break;
                case 'demon':
                    $rating->auto = false;
                    $rating->demon = true;
                    $rating->demon_difficulty = LevelRatingDemonDifficulty::NA;
                    break;
                case 'easy_demon':
                    $rating->auto = false;
                    $rating->demon = true;
                    $rating->demon_difficulty = LevelRatingDemonDifficulty::EASY;
                    break;
                case 'medium_demon':
                    $rating->auto = false;
                    $rating->demon = true;
                    $rating->demon_difficulty = LevelRatingDemonDifficulty::MEDIUM;
                    break;
                case 'hard_demon':
                    $rating->auto = false;
                    $rating->demon = true;
                    $rating->demon_difficulty = LevelRatingDemonDifficulty::HARD;
                    break;
                case 'insane_demon':
                    $rating->auto = false;
                    $rating->demon = true;
                    $rating->demon_difficulty = LevelRatingDemonDifficulty::INSANE;
                    break;
                case 'extreme_demon':
                    $rating->auto = false;
                    $rating->demon = true;
                    $rating->demon_difficulty = LevelRatingDemonDifficulty::EXTREME;
                    break;
                default:
                    $rating->auto = false;
                    $rating->demon = false;
                    break;
            }
        } else {
            $rating->difficulty = LevelRatingService::guessDifficultyFromStars($stars);
            $rating->demon_difficulty = LevelRatingDemonDifficulty::NA;
            $rating->auto = $stars === 1;
            $rating->demon = $stars >= 10;
        }

        $rating->save();
        event(new LevelRated($this->level));

        return __('gdcn.game.command.level_rate_success');
    }

    protected function delete(): string
    {
        if (!$this->currentOperatorIsLevelOwner && !$this->account->can('delete-level')) {
            return __('gdcn.game.command.no_permission');
        }

        $this->level->delete();
        (new GameLevelDataStorageService)->delete(['id' => $this->level->id]);

        return __('gdcn.game.command.level_delete_success');
    }

    protected function setAudioTrack(): string
    {
        if (!$this->currentOperatorIsLevelOwner && !$this->account->can('mod-level')) {
            return __('gdcn.game.command.no_permission');
        }

        $availableValue = [-1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37];
        $newValue = (int)($this->arguments['value'] ?? $this->parameters[1]);

        if (!in_array($newValue, $availableValue)) {
            return __('gdcn.game.command.level_mod_failed_invalid_value', [
                'value' => $newValue
            ]);
        }

        $this->level->audio_track = $newValue;
        $this->level->song = 0;

        return __('gdcn.game.command.level_update_success');
    }

    protected function setSong(): string
    {
        if (!$this->currentOperatorIsLevelOwner && !$this->account->can('mod-level')) {
            return __('gdcn.game.command.no_permission');
        }

        $newValue = (int)($this->arguments['value'] ?? $this->parameters[1]);
        $customSongOffset = config('gdcn.game.custom_song_offset', 10000000);

        if ($newValue >= $customSongOffset && !CustomSong::where('id', $newValue - $customSongOffset)->exists()) {
            return __('gdcn.game.command.level_song_update_failed_custom_not_found', [
                'value' => $newValue
            ]);
        }

        $this->level->audio_track = 0;
        $this->level->song = $newValue;

        return __('gdcn.game.command.level_update_success');
    }
}
