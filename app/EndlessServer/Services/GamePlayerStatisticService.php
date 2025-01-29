<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\PlayerDemonRecord;
use App\EndlessServer\Models\PlayerStatistic;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelRatingDemonDifficulties;

readonly class GamePlayerStatisticService
{
	public function initialize(int $playerId): true
	{
		$exists = PlayerStatistic::query()
			->where('player_id', $playerId)
			->exists();

		if ($exists) {
			return true;
		}

		PlayerStatistic::query()
			->create([
				'player_id' => $playerId,
				'creator_points' => 0,
				'completed_dailies_count' => 0,
				'completed_weeklies_count' => 0,
				'completed_classic_auto_count' => 0,
				'completed_classic_easy_count' => 0,
				'completed_classic_normal_count' => 0,
				'completed_classic_hard_count' => 0,
				'completed_classic_harder_count' => 0,
				'completed_classic_insane_count' => 0,
				'completed_platformer_auto_count' => 0,
				'completed_platformer_easy_count' => 0,
				'completed_platformer_normal_count' => 0,
				'completed_platformer_hard_count' => 0,
				'completed_platformer_harder_count' => 0,
				'completed_platformer_insane_count' => 0,
				'completed_classic_easy_demons_count' => 0,
				'completed_classic_medium_demons_count' => 0,
				'completed_classic_hard_demons_count' => 0,
				'completed_classic_insane_demons_count' => 0,
				'completed_classic_extreme_demons_count' => 0,
				'completed_platformer_easy_demons_count' => 0,
				'completed_platformer_medium_demons_count' => 0,
				'completed_platformer_hard_demons_count' => 0,
				'completed_platformer_insane_demons_count' => 0,
				'completed_platformer_extreme_demons_count' => 0,
				'completed_gauntlet_levels_count' => 0,
				'completed_gauntlet_demon_levels_count' => 0
			]);

		return true;
	}

	public function updateInterval(int $playerId, int $completed_dailies_count, int $completed_weeklies_count): void
	{
		PlayerStatistic::query()
			->where('player_id', $playerId)
			->update([
				'completed_dailies_count' => $completed_dailies_count,
				'completed_weeklies_count' => $completed_weeklies_count
			]);
	}

	public function updatePlatformer(int $playerId, int $completed_platformer_auto_count, int $completed_platformer_easy_count, int $completed_platformer_normal_count, int $completed_platformer_hard_count, int $completed_platformer_harder_count, int $completed_platformer_insane_count): void
	{
		PlayerStatistic::query()
			->where('player_id', $playerId)
			->update([
				'completed_platformer_auto_count' => $completed_platformer_auto_count,
				'completed_platformer_easy_count' => $completed_platformer_easy_count,
				'completed_platformer_normal_count' => $completed_platformer_normal_count,
				'completed_platformer_hard_count' => $completed_platformer_hard_count,
				'completed_platformer_harder_count' => $completed_platformer_harder_count,
				'completed_platformer_insane_count' => $completed_platformer_insane_count
			]);
	}

	public function updateClassic(int $playerId, int $completed_classic_auto_count, int $completed_classic_easy_count, int $completed_classic_normal_count, int $completed_classic_hard_count, int $completed_classic_harder_count, int $completed_classic_insane_count): void
	{
		PlayerStatistic::query()
			->where('player_id', $playerId)
			->update([
				'completed_classic_auto_count' => $completed_classic_auto_count,
				'completed_classic_easy_count' => $completed_classic_easy_count,
				'completed_classic_normal_count' => $completed_classic_normal_count,
				'completed_classic_hard_count' => $completed_classic_hard_count,
				'completed_classic_harder_count' => $completed_classic_harder_count,
				'completed_classic_insane_count' => $completed_classic_insane_count
			]);
	}

	public function updateDemon(int $playerId, array $ids): void
	{
		$this->updateDemonClassic($playerId, $ids);
		$this->updateDemonPlatformer($playerId, $ids);
		$this->updateDemonRecords($playerId, $ids);
	}

	protected function updateDemonClassic(int $playerId, array $ids): void
	{
		PlayerStatistic::query()
			->where('player_id', $playerId)
			->update([
				'completed_classic_easy_demons_count' => Level::query()
					->whereIn('id', $ids)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EASY->value);
					})
					->count(),

				'completed_classic_medium_demons_count' => Level::query()
					->whereIn('id', $ids)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::MEDIUM->value);
					})
					->count(),

				'completed_classic_hard_demons_count' => Level::query()
					->whereIn('id', $ids)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::HARD->value);
					})
					->count(),

				'completed_classic_insane_demons_count' => Level::query()
					->whereIn('id', $ids)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::INSANE->value);
					})
					->count(),

				'completed_classic_extreme_demons_count' => Level::query()
					->whereIn('id', $ids)
					->whereNot('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EXTREME->value);
					})
					->count(),
			]);
	}

	protected function updateDemonPlatformer(int $playerId, array $ids): void
	{
		PlayerStatistic::query()
			->where('player_id', $playerId)
			->update([
				'completed_platformer_easy_demons_count' => Level::query()
					->whereIn('id', $ids)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EASY->value);
					})
					->count(),

				'completed_platformer_medium_demons_count' => Level::query()
					->whereIn('id', $ids)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::MEDIUM->value);
					})
					->count(),

				'completed_platformer_hard_demons_count' => Level::query()
					->whereIn('id', $ids)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::HARD->value);
					})
					->count(),

				'completed_platformer_insane_demons_count' => Level::query()
					->whereIn('id', $ids)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::INSANE->value);
					})
					->count(),

				'completed_platformer_extreme_demons_count' => Level::query()
					->whereIn('id', $ids)
					->where('length', GeometryDashLevelLengths::PLATFORMER->value)
					->whereHas('rating', function ($query) {
						$query->where('demon', true);
						$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EXTREME->value);
					})
					->count()
			]);
	}

	protected function updateDemonRecords(int $playerId, array $ids): void
	{
		foreach ($ids as $id) {
			$exists = PlayerDemonRecord::query()
				->where('player_id', $playerId)
				->where('level_id', $id)
				->exists();

			if ($exists) {
				continue;
			}

			PlayerDemonRecord::query()
				->create([
					'player_id' => $playerId,
					'level_id' => $id
				]);
		}
	}

	public function updateGauntlet(int $playerId, int $completed_gauntlet_levels_count, int $completed_gauntlet_demon_levels_count): void
	{
		PlayerStatistic::query()
			->where('player_id', $playerId)
			->update([
				'completed_gauntlet_levels_count' => $completed_gauntlet_levels_count,
				'completed_gauntlet_demon_levels_count' => $completed_gauntlet_demon_levels_count
			]);
	}
}