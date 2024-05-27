<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\LevelRatingDifficulty;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\LevelRateDemonRequest;
use App\Http\Requests\GDCS\Game\LevelRateStarRequest;
use App\Http\Requests\GDCS\Game\LevelSuggestStarRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelDemonDifficultySuggestion;
use App\Models\GDCS\LevelRating;
use App\Models\GDCS\LevelRatingSuggestion;
use App\Models\GDCS\LevelStarSuggestion;
use App\Services\Game\LevelRatingService;

class LevelRatingController extends Controller
{
	use GameLog;

	/**
	 * @throws GeometryDashChineseServerException
	 */
	public function rateStars(LevelRateStarRequest $request): int
	{
		$data = $request->validated();
		$config = config('gdcn.game.level_rating_suggestions.stars');

		if (!$config['enabled']) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_stars_rate_failed_suggestion_not_enabled'), gameResponse: Response::GAME_LEVEL_RATING_STARS_RATE_FAILED_SUGGESTION_NOT_ENABLED->value);
		}

		$level = Level::query()
			->find($data['levelID']);

		if (!$level) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_stars_rate_failed_level_not_found'), gameResponse: Response::GAME_LEVEL_RATING_STARS_RATE_FAILED_LEVEL_NOT_FOUND->value);
		}

		if (!$config['overwrite_able'] && $level->rating->difficulty !== LevelRatingDifficulty::NA) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_stars_rate_failed_overwrite_disabled'), gameResponse: Response::GAME_LEVEL_RATING_STARS_RATE_FAILED_OVERWRITE_DISABLED->value);
		}

		$record = LevelStarSuggestion::query()
			->firstOrCreate([
				'user_id' => $request->user->id,
				'level_id' => $data['levelID']
			], [
				'stars' => $data['stars'],
				'ip' => $request->ip()
			]);

		if (!$record->wasRecentlyCreated) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_stars_rate_failed_already_exists'), gameResponse: Response::GAME_LEVEL_RATING_STARS_RATE_FAILED_ALREADY_EXISTS->value);
		}

		$query = LevelStarSuggestion::query()
			->where('level_id', $data['levelID']);

		$count = $query->count();
		if ($count >= $config['minimum_count']) {
			$value = $query->average('stars');

			if ($value < 2) {
				$value = 2;
			}

			if ($value > 9) {
				$value = 9;
			}

			LevelRating::updateOrCreate([
				'level_id' => $data['levelID']
			], [
				'stars' => $config['assign_stars'] ? $value : 0,
				'difficulty' => LevelRatingService::guessDifficultyFromStars($value)->value
			]);

			$query->delete();
		}

		$this->logGame(__('gdcn.game.action.level_rating_stars_rate_success'));
		return Response::GAME_LEVEL_RATING_STARS_RATE_SUCCESS->value;
	}

	/**
	 * @throws GeometryDashChineseServerException
	 */
	public function rateDemon(LevelRateDemonRequest $request): int
	{
		$data = $request->validated();
		$config = config('gdcn.game.level_rating_suggestions.demon');

		if (!$config['enabled']) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_demon_difficulty_rate_failed_suggestion_not_enabled'), gameResponse: Response::GAME_LEVEL_RATING_DEMON_DIFFICULTY_RATE_FAILED_SUGGESTION_NOT_ENABLED->value);
		}

		$level = Level::query()
			->find($data['levelID']);

		if (!$level) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_demon_difficulty_rate_failed_level_not_found'), gameResponse: Response::GAME_LEVEL_RATING_DEMON_DIFFICULTY_RATE_FAILED_LEVEL_NOT_FOUND->value);
		}

		if (!$config['overwrite_able'] && $level->rating->demon_difficulty !== LevelRatingDemonDifficulty::NA) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_demon_difficulty_rate_failed_overwrite_disabled'), gameResponse: Response::GAME_LEVEL_RATING_DEMON_DIFFICULTY_RATE_FAILED_OVERWRITE_DISABLED->value);
		}

		$record = LevelDemonDifficultySuggestion::query()
			->firstOrCreate([
				'user_id' => $request->user->id,
				'level_id' => $data['levelID']
			], [
				'demon_diff' => $data['rating'],
				'ip' => $request->ip()
			]);

		if (!$record->wasRecentlyCreated) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_demon_difficulty_rate_failed_already_exists'), gameResponse: Response::GAME_LEVEL_RATING_DEMON_DIFFICULTY_RATE_FAILED_ALREADY_EXISTS->value);
		}

		$query = LevelDemonDifficultySuggestion::query()
			->where('level_id', $data['levelID']);

		$count = $query->count();
		if ($count >= $config['minimum_count']) {
			$value = $query->average('demon_diff');

			LevelRating::updateOrCreate([
				'level_id' => $data['levelID']
			], [
				'demon_difficulty' => LevelRatingService::guessDemonDifficultyFromRating($value)->value
			]);

			$query->delete();
		}

		$this->logGame(__('gdcn.game.action.level_rating_demon_difficulty_rate_success'));
		return Response::GAME_LEVEL_RATING_DEMON_DIFFICULTY_RATE_SUCCESS->value;
	}

	/**
	 * @throws GeometryDashChineseServerException
	 */
	public function suggestStars(LevelSuggestStarRequest $request): int
	{
		$data = $request->validated();
		$config = config('gdcn.game.level_rating_suggestions.suggest');

		if (!$config['enabled']) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_suggest_failed_suggestion_not_enabled'), gameResponse: Response::GAME_LEVEL_RATING_SUGGEST_FAILED_SUGGESTION_NOT_ENABLED->value);
		}

		$level = Level::query()
			->find($data['levelID']);

		if (!$level) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_suggest_failed_level_not_found'), gameResponse: Response::GAME_LEVEL_RATING_SUGGEST_FAILED_LEVEL_NOT_FOUND->value);
		}

		if (!$config['overwrite_able'] && $level->rating->stars > 0) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_suggest_failed_overwrite_disabled'), gameResponse: Response::GAME_LEVEL_RATING_SUGGEST_FAILED_OVERWRITE_DISABLED->value);
		}

		$record = LevelRatingSuggestion::query()
			->firstOrCreate([
				'account_id' => $request->account->id,
				'level_id' => $data['levelID']
			], [
				'rating' => $data['stars'],
				'featured' => $data['feature']
			]);

		if (!$record->wasRecentlyCreated) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.level_rating_suggest_failed_already_exists'), gameResponse: Response::GAME_LEVEL_RATING_SUGGEST_FAILED_ALREADY_EXISTS->value);
		}

		$query = LevelRatingSuggestion::query()
			->where('level_id', $data['levelID']);

		$count = $query->count();
		if ($count >= $config['minimum_count']) {
			$value = $query->average('rating');

			if ($value < 1) {
				$value = 1;
			}

			if ($value > 10) {
				$value = 10;
			}

			LevelRating::updateOrCreate([
				'level_id' => $data['levelID']
			], [
				'stars' => $value,
				'difficulty' => LevelRatingService::guessDifficultyFromStars($value)->value,
				'auto' => $value === 1,
				'demon' => $value === 10
			]);

			$query->delete();
		}

		$this->logGame(__('gdcn.game.action.level_rating_suggest_success'));
		return Response::GAME_LEVEL_RATING_SUGGEST_SUCCESS->value;
	}
}
