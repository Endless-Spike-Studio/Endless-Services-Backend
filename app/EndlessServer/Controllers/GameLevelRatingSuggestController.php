<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\LevelDemonRatingSuggest;
use App\EndlessServer\Models\LevelRatingStarSuggest;
use App\EndlessServer\Models\LevelRatingSuggest;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Requests\GameLevelRatingSuggestDemonVoteRequest;
use App\EndlessServer\Requests\GameLevelRatingSuggestRequest;
use App\EndlessServer\Requests\GameLevelRatingSuggestStarsVoteRequest;
use App\GeometryDash\Enums\GeometryDashLevelRatingDemonDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelRatingEpicTypes;
use App\GeometryDash\Enums\GeometryDashLevelRatingSuggestDemonModes;
use App\GeometryDash\Enums\GeometryDashLevelRatingSuggestDemonRatings;
use App\GeometryDash\Enums\GeometryDashLevelRatingSuggestFeatureTypes;
use App\GeometryDash\Enums\GeometryDashModLevels;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

readonly class GameLevelRatingSuggestController
{
	public function voteStars(GameLevelRatingSuggestStarsVoteRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$level = Level::query()
			->where('id', $data['levelID'])
			->first();

		$exists = LevelRatingStarSuggest::query()
			->where('player_id', $player->id)
			->where('level_id', $level->id)
			->exists();

		if ($exists) {
			return GeometryDashResponses::LEVEL_RATING_SUGGEST_VOTE_STARS_FAILED_ALREADY_VOTED->value;
		}

		LevelRatingStarSuggest::create([
			'player_id' => $player->id,
			'level_id' => $level->id,
			'stars' => $data['stars']
		]);

		if (config('services.endless.server.level_rating_suggest.enabled')) {
			if ($level->rating === null || config('services.endless.server.level_rating_suggest.overrideable')) {
				$query = LevelRatingStarSuggest::query()
					->where('level_id', $data['levelID'])
					->whereNull('apply_at');

				if ($query->count() > config('services.endless.server.level_rating_suggest.min_votes')) {
					$stars = $query->average('stars');

					if ($stars < 1) {
						$stars = 1;
					}

					if ($stars > 10) {
						$stars = 10;
					}

					$level->rating->stars = $stars;
					$level->rating->save();

					$query->update([
						'apply_at' => now()
					]);
				}
			}
		}

		return GeometryDashResponses::LEVEL_RATING_SUGGEST_VOTE_STARS_SUCCESS->value;
	}

	public function voteDemon(GameLevelRatingSuggestDemonVoteRequest $request): int
	{
		$data = $request->validated();

		if (isset($data['mode']) && $data['mode'] === GeometryDashLevelRatingSuggestDemonModes::MOD->value) {
			return $this->suggestDemon($request);
		}

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$level = Level::query()
			->where('id', $data['levelID'])
			->first();

		if ($level->rating === null || $level->rating->demon_difficulty === null) {
			return GeometryDashResponses::LEVEL_RATING_SUGGEST_VOTE_DEMON_FAILED_NO_RATING->value;
		}

		$exists = LevelDemonRatingSuggest::query()
			->where('player_id', $player->id)
			->where('level_id', $level->id)
			->exists();

		if ($exists) {
			return GeometryDashResponses::LEVEL_RATING_SUGGEST_VOTE_DEMON_FAILED_ALREADY_VOTED->value;
		}

		LevelDemonRatingSuggest::create([
			'player_id' => $player->id,
			'level_id' => $level->id,
			'rating' => $data['rating']
		]);

		if (config('services.endless.server.demon_rating_suggest.enabled')) {
			if ($level->rating->demon_difficulty->value === GeometryDashLevelRatingDemonDifficulties::UNKNOWN->value || config('services.endless.server.demon_rating_suggest.overrideable')) {
				$query = LevelDemonRatingSuggest::query()
					->where('level_id', $data['levelID'])
					->whereNull('apply_at');

				if ($query->count() > config('services.endless.server.demon_rating_suggest.min_votes')) {
					$rating = $query->average('rating');

					if ($rating < 1) {
						$rating = 1;
					}

					if ($rating > 5) {
						$rating = 5;
					}

					$demonDifficulty = match ($rating) {
						1 => GeometryDashLevelRatingDemonDifficulties::EASY->value,
						2 => GeometryDashLevelRatingDemonDifficulties::MEDIUM->value,
						3 => GeometryDashLevelRatingDemonDifficulties::HARD->value,
						4 => GeometryDashLevelRatingDemonDifficulties::INSANE->value,
						5 => GeometryDashLevelRatingDemonDifficulties::EXTREME->value,
						default => GeometryDashLevelRatingDemonDifficulties::UNKNOWN->value
					};

					$level->rating->demon_difficulty = $demonDifficulty;
					$level->rating->save();

					$query->update([
						'apply_at' => now()
					]);
				}
			}
		}

		return GeometryDashResponses::LEVEL_RATING_SUGGEST_VOTE_DEMON_SUCCESS->value;
	}

	public function suggestDemon(GameLevelRatingSuggestDemonVoteRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$level = Level::query()
			->where('id', $data['levelID'])
			->first();

		if ($level->rating === null || $level->rating->demon_difficulty === null) {
			return GeometryDashResponses::LEVEL_RATING_SUGGEST_DEMON_FAILED_NO_RATING->value;
		}

		$exists = LevelDemonRatingSuggest::query()
			->where('player_id', $player->id)
			->where('level_id', $level->id)
			->exists();

		if ($exists) {
			return GeometryDashResponses::LEVEL_RATING_SUGGEST_DEMON_FAILED_ALREADY_VOTED->value;
		}

		LevelDemonRatingSuggest::create([
			'player_id' => $player->id,
			'level_id' => $level->id,
			'rating' => $data['rating']
		]);

		if (config('services.endless.server.mod_demon_rating_suggest.enabled')) {
			if ($level->rating->demon_difficulty->value === GeometryDashLevelRatingDemonDifficulties::UNKNOWN->value || config('services.endless.server.mod_demon_rating_suggest.overrideable')) {
				$query = LevelDemonRatingSuggest::query()
					->whereHas('player.account.roles', function (Builder $query) {
						$query->whereNot('mod_level', GeometryDashModLevels::PLAYER->value);
					})
					->where('level_id', $data['levelID'])
					->whereNull('apply_at');

				if ($query->count() > config('services.endless.server.mod_demon_rating_suggest.min_votes')) {
					$rating = $query->average('rating');

					if ($rating < 1) {
						$rating = 1;
					}

					if ($rating > 5) {
						$rating = 5;
					}

					$demonDifficulty = match ($rating) {
						GeometryDashLevelRatingSuggestDemonRatings::EASY_DEMON->value => GeometryDashLevelRatingDemonDifficulties::EASY->value,
						GeometryDashLevelRatingSuggestDemonRatings::MEDIUM_DEMON->value => GeometryDashLevelRatingDemonDifficulties::MEDIUM->value,
						GeometryDashLevelRatingSuggestDemonRatings::HARD_DEMON->value => GeometryDashLevelRatingDemonDifficulties::HARD->value,
						GeometryDashLevelRatingSuggestDemonRatings::INSANE_DEMON->value => GeometryDashLevelRatingDemonDifficulties::INSANE->value,
						GeometryDashLevelRatingSuggestDemonRatings::EXTREME_DEMON->value => GeometryDashLevelRatingDemonDifficulties::EXTREME->value,
						default => GeometryDashLevelRatingDemonDifficulties::UNKNOWN->value
					};

					$level->rating->demon_difficulty = $demonDifficulty;
					$level->rating->save();

					$query->update([
						'apply_at' => now()
					]);
				}
			}
		}

		return GeometryDashResponses::LEVEL_RATING_SUGGEST_DEMON_SUCCESS->value;
	}

	public function suggestStars(GameLevelRatingSuggestRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER)->user();


		$level = Level::query()
			->where('id', $data['levelID'])
			->first();

		$exists = LevelRatingSuggest::query()
			->where('player_id', $player->id)
			->where('level_id', $level->id)
			->exists();

		if ($exists) {
			return GeometryDashResponses::LEVEL_RATING_SUGGEST_FAILED_ALREADY_VOTED->value;
		}

		LevelRatingSuggest::create([
			'player_id' => $player->id,
			'level_id' => $level->id,
			'stars' => $data['stars'],
			'feature' => $data['feature']
		]);

		if (config('services.endless.server.mod_level_rating_suggest.enabled')) {
			if ($level->rating === null || config('services.endless.server.mod_level_rating_suggest.overrideable')) {
				$query = LevelRatingSuggest::query()
					->whereHas('player.account.roles', function (Builder $query) {
						$query->whereNot('mod_level', GeometryDashModLevels::PLAYER->value);
					})
					->where('level_id', $data['levelID'])
					->whereNull('apply_at');

				if ($query->count() > config('services.endless.server.mod_level_rating_suggest.min_votes')) {
					$stars = $query->average('stars');

					if ($stars < 1) {
						$stars = 1;
					}

					if ($stars > 10) {
						$stars = 10;
					}

					$feature = $query->average('feature');

					if ($feature < 0) {
						$feature = 0;
					}

					if ($feature > 4) {
						$feature = 4;
					}

					$level->rating->stars = $stars;

					switch ($feature) {
						case GeometryDashLevelRatingSuggestFeatureTypes::FEATURE->value:
							$score = $query->where('feature', GeometryDashLevelRatingSuggestFeatureTypes::FEATURE->value)->count();

							if ($score < 1) {
								$score = 1;
							}

							$level->rating->feature = $score;
							break;
						case GeometryDashLevelRatingSuggestFeatureTypes::EPIC->value:
							$level->rating->epic_type = GeometryDashLevelRatingEpicTypes::EPIC->value;
							break;
						case GeometryDashLevelRatingSuggestFeatureTypes::LEGENDARY->value:
							$level->rating->epic_type = GeometryDashLevelRatingEpicTypes::LEGENDARY->value;
							break;
						case GeometryDashLevelRatingSuggestFeatureTypes::MYTHIC->value:
							$level->rating->epic_type = GeometryDashLevelRatingEpicTypes::MYTHIC->value;
							break;
					}

					$level->rating->save();

					$query->update([
						'apply_at' => now()
					]);
				}
			}
		}

		return GeometryDashResponses::LEVEL_RATING_SUGGEST_SUCCESS->value;
	}
}