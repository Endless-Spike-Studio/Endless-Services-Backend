<?php

namespace App\Services\GDCS\Game\Command;

use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\LevelRatingDifficulty;
use App\Services\Game\LevelRatingService;
use App\Services\Storage\GameLevelDataStorageService;
use Illuminate\Support\Str;

class LevelCommentService extends BaseCommandService
{
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
                'data' => $stars
            ]);
        }

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
                    'data' => $face
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
        return __('gdcn.game.command.level_rate_success');
    }

    protected function delete(): string
    {
        if (!$this->level->creator->is($this->account->user) && !$this->account->can('rate-level')) {
            return __('gdcn.game.command.no_permission');
        }

        $this->level->delete();
        (new GameLevelDataStorageService)->delete(['id' => $this->level->id]);

        return __('gdcn.game.command.level_delete_success');
    }
}
